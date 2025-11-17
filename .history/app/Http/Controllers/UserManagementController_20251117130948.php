<?php

namespace App\Http\Controllers;

use App\Models\User; // <-- TAMBAHKAN BARIS INI
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // <-- Ini seharusnya sudah ada

class UserManagementController extends Controller
{
    /**
     * Menampilkan halaman daftar user (READ).
     */
    public function index()
    {
        // Kode ini sekarang akan berfungsi karena 'User' sudah dikenali
        $users = User::orderBy('id', 'desc')->get();

        return view('dashboard.userManagement', [
            'users' => $users
        ]);
    }

    // ... sisa controller ...
}