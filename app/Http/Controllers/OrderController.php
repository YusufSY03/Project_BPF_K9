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
    /**
     * Menambahkan item ke keranjang (Session)
     */
    public function addToCart($id)
    {
        $menu = MenuItem::find($id);

        if (!$menu) {
            return redirect()->back()->with('error', 'Menu tidak ditemukan!');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name"     => $menu->name,
                "quantity" => 1,
                "price"    => $menu->price,
                "image"    => $menu->image_url,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('status', 'Berhasil ditambahkan ke keranjang!');
    }

    /**
     * Menampilkan halaman keranjang
     */
    public function cart()
    {
        return view('page.cart');
    }

    /**
     * Menghapus item dari keranjang
     */
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

    /**
     * Menampilkan Halaman Checkout (Konfirmasi)
     */
    public function checkout()
    {
        if (!session('cart') || count(session('cart')) == 0) {
            return redirect()->route('menu');
        }
        return view('page.checkout');
    }

    /**
     * API AJAX: Cek Ongkir dari Peta
     * Dipanggil oleh Javascript di halaman checkout
     */
    public function checkShipping(Request $request)
    {
        // Validasi input
        if(!$request->latitude || !$request->longitude) {
            return response()->json(['status' => 'error', 'message' => 'Lokasi tidak valid']);
        }

        // Panggil fungsi hitungOngkir
        $result = $this->hitungOngkir($request->latitude, $request->longitude);
        
        return response()->json($result);
    }

    /**
     * Memproses Pesanan (Simpan ke Database)
     */
    public function processCheckout(Request $request)
    {
        $cart = session('cart');

        if (!$cart) {
            return redirect()->route('menu')->with('error', 'Keranjang kosong!');
        }

        // Validasi: Lokasi Wajib Diisi
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
        ], [
            'latitude.required' => 'Mohon pilih lokasi pengantaran di peta!',
        ]);

        // Hitung ulang ongkir di server (Keamanan: agar user tidak memanipulasi harga di inspect element)
        $shippingCheck = $this->hitungOngkir($request->latitude, $request->longitude);
        
        // Jika API error saat checkout, defaultkan ke 0 atau tolak (di sini kita defaultkan 0 agar tetap bisa pesan manual)
        $shippingPrice = ($shippingCheck['status'] == 'success') ? $shippingCheck['ongkir'] : 0;

        // Hitung total belanja
        $subtotal = 0;
        foreach ($cart as $id => $details) {
            $subtotal += $details['price'] * $details['quantity'];
        }

        $grandTotal = $subtotal + $shippingPrice;

        // Gunakan Database Transaction
        DB::transaction(function () use ($request, $cart, $grandTotal, $shippingPrice) {

            // 1. Simpan data ke tabel 'orders'
            $order = Order::create([
                'user_id'        => Auth::id(),
                'total_amount'   => $grandTotal,
                'shipping_price' => $shippingPrice, // Simpan ongkir
                'latitude'       => $request->latitude,   // Simpan lat
                'longitude'      => $request->longitude, // Simpan long
                'status'         => 'pending', 
                'payment_method' => $request->payment_method,
                'payment_proof'  => null,
            ]);

            // 2. Simpan detail menu ke tabel 'order_items'
            foreach ($cart as $id => $details) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'menu_item_id' => $id,
                    'quantity'     => $details['quantity'],
                    'price'        => $details['price'],
                ]);
            }
        });

        // 3. Kosongkan keranjang belanja
        session()->forget('cart');

        // 4. Arahkan ke halaman menu
        return redirect()->route('menu')->with('status', 'Pesanan berhasil! Total: Rp ' . number_format($grandTotal, 0, ',', '.'));
    }
    
    /**
     * Fungsi Logika Hitung Jarak & Ongkir
     */
    private function hitungOngkir($userLat, $userLong)
    {
        // 1. Ambil Config dari .env
        $apiKey = env('ORS_API_KEY');
        $storeLat = env('STORE_LAT');
        $storeLong = env('STORE_LONG');
        $pricePerKm = env('PRICE_PER_KM', 3000); // Default 3000 jika env kosong

        // Cek apakah API Key ada
        if (empty($apiKey)) {
            return ['status' => 'error', 'message' => 'API Key belum disetting di .env'];
        }

        // Cek apakah Koordinat Toko ada
        if (empty($storeLat) || empty($storeLong)) {
            return ['status' => 'error', 'message' => 'Koordinat Toko belum disetting di .env'];
        }

        try {
            // 2. Request ke OpenRouteService
            // PENTING: verify => false digunakan untuk bypass masalah SSL di Localhost/Windows
            $response = Http::withOptions(['verify' => false])->get('https://api.openrouteservice.org/v2/directions/driving-car', [
                'api_key' => $apiKey,
                'start' => "$storeLong,$storeLat", // Ingat: Longitude dulu
                'end' => "$userLong,$userLat"      // Koordinat User
            ]);
    
            // 3. Cek Response
            if ($response->successful()) {
                $data = $response->json();
                
                // Validasi apakah rute ditemukan (misal beda pulau atau terlalu jauh)
                if(!isset($data['features'][0]['properties']['segments'][0]['distance'])) {
                    return ['status' => 'error', 'message' => 'Rute tidak ditemukan (Mungkin lokasi tidak terjangkau mobil)'];
                }

                $jarakMeter = $data['features'][0]['properties']['segments'][0]['distance'];
                $jarakKm = round($jarakMeter / 1000, 2); // Ubah ke KM
    
                $totalOngkir = $jarakKm * $pricePerKm;
    
                return [
                    'status' => 'success',
                    'jarak' => $jarakKm,
                    'ongkir' => $totalOngkir
                ];
            } else {
                // Jika API merespon error (misal 401 Unauthorized, 403 Forbidden, Quota Exceeded)
                return [
                    'status' => 'error', 
                    'message' => 'API Error (' . $response->status() . '): ' . $response->body()
                ];
            }
        } catch (\Exception $e) {
            // Jika koneksi internet mati atau DNS error
            return [
                'status' => 'error', 
                'message' => 'Koneksi Gagal: ' . $e->getMessage()
            ];
        }
    }
}