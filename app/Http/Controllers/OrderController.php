<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Untuk transaksi database
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    /**
     * Menambahkan item ke keranjang (Session)
     */
    public function addToCart($id)
    {
        $menu = MenuItem::find($id);

        if(!$menu) {
            return redirect()->back()->with('error', 'Menu tidak ditemukan!');
        }

        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
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
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
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
        if(!session('cart') || count(session('cart')) == 0) {
            return redirect()->route('menu');
        }
        return view('page.checkout');
    }

    /**
     * Memproses Pesanan (Simpan ke Database)
     */
    public function processCheckout(Request $request)
    {
        $cart = session('cart');

        if(!$cart) {
            return redirect()->route('menu')->with('error', 'Keranjang kosong!');
        }

        // Hitung total bayar
        $totalAmount = 0;
        foreach($cart as $id => $details) {
            $totalAmount += $details['price'] * $details['quantity'];
        }

        // Gunakan Database Transaction agar aman (semua tersimpan atau tidak sama sekali)
        DB::transaction(function () use ($request, $cart, $totalAmount) {
            
            // 1. Simpan data ke tabel 'orders'
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'status' => 'pending', // Default status
                'payment_method' => $request->payment_method,
                // payment_proof nanti bisa ditambahkan fitur upload gambarnya
                'payment_proof' => null, 
            ]);

            // 2. Simpan detail menu ke tabel 'order_items'
            foreach($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);
            }
        });

        // 3. Kosongkan keranjang belanja
        session()->forget('cart');

        // 4. Arahkan ke halaman menu (atau halaman sukses nanti)
        return redirect()->route('menu')->with('status', 'Pesanan berhasil dibuat! Mohon tunggu konfirmasi Admin.');
    }
}