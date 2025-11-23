<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem; // <-- Import Model Menu

class PageController extends Controller
{
    public function home()
    {
        // Ambil 3 menu terbaru/terfavorit untuk ditampilkan di Home
        $featuredMenus = MenuItem::where('is_active', true)
                                 ->orderBy('id', 'desc')
                                 ->take(3)
                                 ->get();

        return view('page.home', [
            'featuredMenus' => $featuredMenus
        ]);
    }

    public function menu()
    {
        // Ambil SEMUA menu yang statusnya 'is_active' = true
        $menuItems = MenuItem::where('is_active', true)
                             ->orderBy('category', 'asc') // Urutkan berdasarkan kategori
                             ->get();

        return view('page.menu', [
            'menuItems' => $menuItems
        ]);
    }

    public function about()
    {
        return view('page.about');
    }
}