<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Menampilkan halaman Home.
     */
    public function home()
    {
        return view('home');
    }

    /**
     * Menampilkan halaman Menu.
     */
    public function menu()
    {
        return view('menu');
    }

    /**
     * Menampilkan halaman About.
     */
    public function about()
    {
        return view('about');
    }
}
