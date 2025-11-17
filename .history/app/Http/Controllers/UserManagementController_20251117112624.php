<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
        // 1. Ambil semua data user dari database
        // Kita urutkan berdasarkan ID terbaru
        $users = User::orderBy('id', 'desc')->get();

        // 2. Kirim data users ke view
        return view('dashboard.userManagement', [
            'users' => $users
        ]);
    }
}
