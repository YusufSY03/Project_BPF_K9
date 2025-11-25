<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    // ... (Fungsi addToCart, cart, dan remove TETAP SAMA, tidak diubah) ...
    public function addToCart($id)
    {
        $menu = MenuItem::find($id);
        if (!$menu) return redirect()->back()->with('error', 'Menu tidak ditemukan!');

        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $menu->name,
                "quantity" => 1,
                "price" => $menu->price,
                "image" => $menu->image_url
            ];
        }
        session()->put('cart', $cart);
        return redirect()->back()->with('status', 'Berhasil ditambahkan ke keranjang!');
    }

    public function cart()
    {
        return view('page.cart');
    }

    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('status', 'Menu berhasil dihapus dari keranjang!');
        }
    }

    public function checkout()
    {
        if (!session('cart') || count(session('cart')) == 0) {
            return redirect()->route('menu');
        }
        return view('page.checkout');
    }

    // --- FITUR BARU: AJAX Check Ongkir ---
    public function checkShipping(Request $request)
    {
        // Validasi input
        if(!$request->latitude || !$request->longitude) {
            return response()->json(['status' => 'error', 'message' => 'Lokasi tidak valid']);
        }

        // Panggil fungsi hitungOngkir yang sudah kamu buat
        $result = $this->hitungOngkir($request->latitude, $request->longitude);
        
        return response()->json($result);
    }

    public function processCheckout(Request $request)
    {
        $cart = session('cart');

        if (!$cart) {
            return redirect()->route('menu')->with('error', 'Keranjang kosong!');
        }

        // Validasi Lokasi Wajib Diisi
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ], [
            'latitude.required' => 'Mohon pilih lokasi pengantaran di peta!',
        ]);

        // Hitung ulang ongkir di server (agar tidak bisa dimanipulasi user)
        $shippingResult = $this->hitungOngkir($request->latitude, $request->longitude);
        $shippingPrice = ($shippingResult['status'] == 'success') ? $shippingResult['ongkir'] : 0;

        // Hitung total bayar (Subtotal + Ongkir)
        $subtotal = 0;
        foreach ($cart as $id => $details) {
            $subtotal += $details['price'] * $details['quantity'];
        }
        $grandTotal = $subtotal + $shippingPrice;

        DB::transaction(function () use ($request, $cart, $grandTotal, $shippingPrice) {
            // 1. Simpan data ke tabel 'orders' dengan ongkir & lokasi
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $grandTotal,
                'shipping_price' => $shippingPrice, // Simpan biaya ongkir
                'latitude' => $request->latitude,   // Simpan lat
                'longitude' => $request->longitude, // Simpan long
                'status' => 'pending', 
                'payment_method' => $request->payment_method,
                'payment_proof' => null,
            ]);

            // 2. Simpan detail menu
            foreach ($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);
            }
        });

        session()->forget('cart');
        return redirect()->route('menu')->with('status', 'Pesanan berhasil! Total: Rp ' . number_format($grandTotal));
    }
    
    // Fungsi Hitung Ongkir (Milikmu, sedikit disesuaikan return-nya untuk error handling)
    public function hitungOngkir($userLat, $userLong)
    {
        $apiKey = env('ORS_API_KEY');
        $storeLat = env('STORE_LAT');
        $storeLong = env('STORE_LONG');
        $pricePerKm = env('PRICE_PER_KM', 3000); // Default 3000 jika env kosong

        if (empty($apiKey)) {
            return ['status' => 'error', 'message' => 'API Key belum disetting'];
        }

        try {
            $response = Http::get('https://api.openrouteservice.org/v2/directions/driving-car', [
                'api_key' => $apiKey,
                'start' => "$storeLong,$storeLat",
                'end' => "$userLong,$userLat"
            ]);
    
            if ($response->successful()) {
                $data = $response->json();
                
                // Cek apakah data segments tersedia
                if(!isset($data['features'][0]['properties']['segments'][0]['distance'])) {
                    return ['status' => 'error', 'message' => 'Rute tidak ditemukan (terlalu jauh/beda pulau)'];
                }

                $jarakMeter = $data['features'][0]['properties']['segments'][0]['distance'];
                $jarakKm = round($jarakMeter / 1000, 2);
    
                $totalOngkir = $jarakKm * $pricePerKm;
    
                return [
                    'status' => 'success',
                    'jarak' => $jarakKm,
                    'ongkir' => $totalOngkir
                ];
            }
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Koneksi API Gagal'];
        }

        return ['status' => 'error', 'message' => 'Gagal menghitung jarak. Cek koneksi atau kuota API.'];
    }
}