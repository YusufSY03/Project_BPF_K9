<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MenuItem; // <-- Jangan lupa import Model Menu

class OrderController extends Controller
{
    public function addToCart($id)
    {
        // 1. Cari menu berdasarkan ID
        $menu = MenuItem::find($id);

        // Jika menu tidak ada, kembalikan error
        if(!$menu) {
            return redirect()->back()->with('error', 'Menu tidak ditemukan!');
        }

        // 2. Ambil keranjang saat ini dari Session (jika kosong, buat array baru)
        $cart = session()->get('cart', []);

        // 3. Cek apakah menu ini sudah ada di keranjang?
        if(isset($cart[$id])) {
            // Jika sudah ada, tambahkan jumlahnya (quantity)
            $cart[$id]['quantity']++;
        } else {
            // Jika belum ada, masukkan sebagai item baru
            $cart[$id] = [
                "name" => $menu->name,
                "quantity" => 1,
                "price" => $menu->price,
                "image" => $menu->image_url
            ];
        }

        // 4. Simpan kembali data keranjang ke Session
        session()->put('cart', $cart);

        // 5. Kembali ke halaman menu dengan pesan sukses
        return redirect()->back()->with('status', 'Berhasil ditambahkan ke keranjang!');
    }
    public function cart()
    {
        return view('page.cart');
    }

    /**
     * Menghapus barang dari keranjang
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
}