<?php

namespace App\Http\Controllers; // <-- Ganti namespace jika salah

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserManagementController extends Controller
{
    /**
     * Menampilkan halaman daftar user (READ).
     */
    public function index()
    {
        // Ambil data user, TAPI jangan tampilkan 'owner'
        // Owner tidak boleh mengedit dirinya sendiri
        $users = User::where('role', '!=', 'owner')
                     ->orderBy('id', 'desc')
                     ->get();

        return view('dashboard.userManagement', [
            'users' => $users
        ]);
    }

    /**
     * Menampilkan formulir untuk membuat user baru (CREATE).
     */
    public function create()
    {
        return view('dashboard.userForm'); // Mengarah ke file baru kita
    }

    /**
     * Menyimpan user baru ke database (STORE).
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,user'], // Pastikan role hanya admin/user
        ]);

        // 2. Buat pengguna baru di database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // 3. Arahkan kembali ke halaman daftar user
        //    Kita akan tambahkan pesan sukses nanti
        return redirect()->route('owner.users.index');
    }
}