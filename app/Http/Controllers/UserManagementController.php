<?php

namespace App\Http;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserManagementController extends Controller
{
    /**
     * Menampilkan halaman daftar user (READ).
     */
    public function index()
    {
        $users = User::where('role', '!=', 'owner')
            ->orderBy('id', 'desc')
            ->get();

        return view('dashboard.userManagement', [
            'users' => $users,
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

        return redirect()->route('owner.users.index')
            ->with('status', 'User baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir untuk mengedit user (EDIT).
     */
    public function edit(User $user)
    {
        if ($user->role === 'owner') {
            return redirect()->route('owner.users.index')
                ->withErrors('Anda tidak bisa mengedit akun Owner.');
        }

        return view('dashboard.userForm', [
            'user' => $user,
        ]);
    }

    /**
     * Menyimpan perubahan user ke database (UPDATE).
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,user'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('owner.users.index')
            ->with('status', 'Data user berhasil diperbarui.');
    }

    // ===============================================
    // FUNGSI BARU UNTUK 'DELETE'
    // ===============================================

    /**
     * Menghapus user dari database (DESTROY).
     */
    public function destroy(User $user)
    {
        // Keamanan ekstra: Pastikan Owner tidak terhapus
        if ($user->role === 'owner') {
            return redirect()->route('owner.users.index')
                ->withErrors('Akun Owner tidak bisa dihapus.');
        }

        // Hapus user
        $user->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('owner.users.index')
            ->with('status', 'User berhasil dihapus.');
    }
}
