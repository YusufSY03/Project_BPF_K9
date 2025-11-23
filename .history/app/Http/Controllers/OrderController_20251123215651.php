<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Penting untuk cek user login

class OrderController extends Controller
{
    // Fungsi untuk memasukkan barang ke keranjang (akan kita isi nanti)
    public function addToCart(Request $request, $menuId)
    {
        // Pastikan hanya user login yang bisa masuk sini (Double protection)
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Logika simpan pesanan akan di sini...
        return redirect()->back()->with('status', 'Menu berhasil masuk keranjang (Simulasi)');
    }
}