<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Menampilkan halaman "About Us".
     *
     * @return \Illuminate\View\View
     */
    public function showAboutPage()
    {
        // Method ini hanya akan me-return atau menampilkan file view
        return view('about'); 
    }
}