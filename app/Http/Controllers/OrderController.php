<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage; // <-- Tambahan untuk file

class OrderController extends Controller
{
    // ... Fungsi Keranjang (addToCart, cart, remove) tetap sama ...
    public function addToCart(Request $request, $id) {
        $menu = MenuItem::find($id);
        if (!$menu) return redirect()->back()->with('error', 'Menu tidak ditemukan!');
        $qty = (int) $request->input('quantity', 1);
        if($qty < 1) $qty = 1;
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) { $cart[$id]['quantity'] += $qty; } 
        else {
            $cart[$id] = ["name" => $menu->name, "quantity" => $qty, "price" => $menu->price, "image" => $menu->image_url];
        }
        session()->put('cart', $cart);
        return redirect()->back()->with('status', "Berhasil menambahkan $qty item ke keranjang!");
    }

    public function cart() { return view('page.cart'); }

    public function remove(Request $request) {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) { unset($cart[$request->id]); session()->put('cart', $cart); }
            return redirect()->back()->with('status', 'Menu berhasil dihapus dari keranjang!');
        }
    }

    // ... Fungsi Checkout ...
    public function checkout() {
        if (!session('cart') || count(session('cart')) == 0) return redirect()->route('menu');
        return view('page.checkout');
    }

    public function checkShipping(Request $request) {
        if(!$request->latitude || !$request->longitude) return response()->json(['status' => 'error', 'message' => 'Lokasi tidak valid']);
        $result = $this->hitungOngkir($request->latitude, $request->longitude);
        return response()->json($result);
    }

    public function processCheckout(Request $request) {
        $cart = session('cart');
        if (!$cart) return redirect()->route('menu')->with('error', 'Keranjang kosong!');

        $request->validate(['latitude' => 'required', 'longitude' => 'required'], ['latitude.required' => 'Mohon pilih lokasi pengantaran di peta!']);

        $shippingCheck = $this->hitungOngkir($request->latitude, $request->longitude);
        $shippingPrice = ($shippingCheck['status'] == 'success') ? $shippingCheck['ongkir'] : 0;

        $subtotal = 0;
        foreach ($cart as $id => $details) { $subtotal += $details['price'] * $details['quantity']; }
        $grandTotal = $subtotal + $shippingPrice;

        DB::transaction(function () use ($request, $cart, $grandTotal, $shippingPrice) {
            $order = Order::create([
                'user_id' => Auth::id(), 'total_amount' => $grandTotal, 'shipping_price' => $shippingPrice,
                'latitude' => $request->latitude, 'longitude' => $request->longitude,
                'status' => 'pending', 'payment_method' => $request->payment_method, 'payment_proof' => null,
            ]);
            foreach ($cart as $id => $details) {
                OrderItem::create(['order_id' => $order->id, 'menu_item_id' => $id, 'quantity' => $details['quantity'], 'price' => $details['price']]);
            }
        });
        session()->forget('cart');
        return redirect()->route('orders.history')->with('status', 'Pesanan berhasil! Total: Rp ' . number_format($grandTotal, 0, ',', '.'));
    }

    public function history() {
        $orders = Order::where('user_id', Auth::id())->with('items.menu')->orderBy('created_at', 'desc')->get();
        return view('page.history', ['orders' => $orders]);
    }

    // === FITUR BARU: UPLOAD BUKTI BAYAR ===
    public function uploadProof(Request $request, Order $order)
    {
        // Pastikan order milik user yang login
        if ($order->user_id !== Auth::id()) {
            return redirect()->back()->withErrors('Anda tidak memiliki akses ke pesanan ini.');
        }

        $request->validate([
            'payment_proof' => 'required|image|max:2048' // Max 2MB
        ]);

        // Simpan gambar ke folder 'payment_proofs'
        if ($request->hasFile('payment_proof')) {
            // Hapus bukti lama jika ada (untuk hemat storage)
            if ($order->payment_proof) {
                Storage::disk('public')->delete($order->payment_proof);
            }
            
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            
            $order->update([
                'payment_proof' => $path
            ]);
        }

        return redirect()->back()->with('status', 'Bukti pembayaran berhasil diupload!');
    }

    private function hitungOngkir($userLat, $userLong) {
        $apiKey = env('ORS_API_KEY');
        $storeLat = env('STORE_LAT');
        $storeLong = env('STORE_LONG');
        $pricePerKm = env('PRICE_PER_KM', 3000);

        if (empty($apiKey) || empty($storeLat) || empty($storeLong)) return ['status' => 'error', 'message' => 'Konfigurasi Toko/API Key belum lengkap di .env'];

        try {
            $response = Http::withOptions(['verify' => false])->get('https://api.openrouteservice.org/v2/directions/driving-car', [
                'api_key' => $apiKey, 'start' => "$storeLong,$storeLat", 'end' => "$userLong,$userLat"
            ]);
            if ($response->successful()) {
                $data = $response->json();
                if(!isset($data['features'][0]['properties']['segments'][0]['distance'])) return ['status' => 'error', 'message' => 'Rute tidak ditemukan'];
                $jarakKm = round($data['features'][0]['properties']['segments'][0]['distance'] / 1000, 2);
                return ['status' => 'success', 'jarak' => $jarakKm, 'ongkir' => $jarakKm * $pricePerKm];
            } else {
                logger()->error('API ORS Error: ' . $response->body());
                return ['status' => 'error', 'message' => 'Gagal menghitung jarak. Server API menolak request.'];
            }
        } catch (\Exception $e) {
            logger()->error('Koneksi Error: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Koneksi internet bermasalah.'];
        }
    }
}