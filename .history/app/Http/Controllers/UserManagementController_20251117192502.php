<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule; // <-- Pastikan ini ada

class UserManagementController extends Controller
{
    /**
     * Menampilkan halaman daftar user (READ).
     */
    public function index()
    {
        // Ambil data user, TAPI jangan tampilkan 'owner'
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
        return view('dashboard.userForm');
    }

    /**
     * Menyimpan user baru ke database (STORE).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,user'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('users.index')
                         ->with('status', 'User baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir untuk mengedit user (EDIT).
     */
    public function edit(User $user)
    {
        // Owner tidak boleh mengedit dirinya sendiri (keamanan)
        if ($user->role === 'owner') {
            return redirect()->route('users.index')
                             ->withErrors('Anda tidak bisa mengedit akun Owner.');
        }

        // Tampilkan form, tapi kirim data $user yang mau diedit
        return view('dashboard.userForm', [
            'user' => $user
        ]);
    }

    /**
     * Menyimpan perubahan user ke database (UPDATE).
     */
    public function update(Request $request, User $user)
    {
        // 1. Validasi
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Email harus unik, tapi abaikan (ignore) email user ini sendiri
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            // Password sekarang opsional (nullable)
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,user'],
        ]);

        // 2. Siapkan data untuk di-update
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // 3. Cek jika password diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // 4. Update data user
        $user->update($data);

        // 5. Redirect dengan pesan sukses
        return redirect()->route('users.index')
                         ->with('status', 'Data user berhasil diperbarui.');
    }

    /**
     * Menghapus user dari database (DESTROY).
     */
    public function destroy(User $user)
    {
        // Keamanan ekstra: Pastikan Owner tidak terhapus
        if ($user->role === 'owner') {
            return redirect()->route('users.index')
                             ->withErrors('Akun Owner tidak bisa dihapus.');
        }

        // Hapus user
        $user->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('users.index')
                         ->with('status', 'User berhasil dihapus.');
    }
}