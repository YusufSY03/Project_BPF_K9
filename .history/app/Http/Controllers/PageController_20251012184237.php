<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        // SEBELUM: return view('home');
        return view('page.home'); // SESUDAH
    }

    public function menu()
    {
        // SEBELUM: return view('menu');
        return view('page.menu'); // SESUDAH
    }

    public function about()
    {
        // SEBELUM: return view('about');
        return view('page.about'); // SESUDAH
    }
}