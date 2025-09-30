<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return "Selamat datang di website UMKM Penjualan Makanan!";
    }

    public function show($id_produk = null)
    {
        if ($id_produk) {
            return "Anda mengakses detail produk makanan dengan ID: " . $id_produk;
        } else {
            return "Masukkan ID produk makanan!";
        }
    }
}
