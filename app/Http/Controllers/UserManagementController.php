<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    // ... fungsi index(), create() ...

    public function index()
    {
        $users = User::where('role', '!=', 'owner')
                     ->orderBy('id', 'desc')
                     ->get();

        return view('dashboard.userManagement', [
            'users' => $users
        ]);
    }

    public function create()
    {
        return view('dashboard.userForm');
    }


    /**
     * Menyimpan user baru ke database (STORE).
     */
    public function store(Request $request)
    {
        // ... validasi ...
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

        // INI YANG PENTING
        // Pastikan ini mengarah ke 'users.index'
        return redirect()->route('users.index')
                         ->with('status', 'User baru berhasil ditambahkan.');
    }

    // ... fungsi edit() ...
    public function edit(User $user)
    {
        if ($user->role === 'owner') {
            return redirect()->route('users.index')
                             ->withErrors('Anda tidak bisa mengedit akun Owner.');
        }
        return view('dashboard.userForm', [
            'user' => $user
        ]);
    }

    /**
     * Menyimpan perubahan user ke database (UPDATE).
     */
    public function update(Request $request, User $user)
    {
        // ... validasi ...
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

        // INI JUGA PENTING
        // Pastikan ini mengarah ke 'users.index'
        return redirect()->route('users.index')
                         ->with('status', 'Data user berhasil diperbarui.');
    }

    // ... fungsi destroy() ...
    public function destroy(User $user)
    {
        if ($user->role === 'owner') {
            return redirect()->route('users.index')
                             ->withErrors('Akun Owner tidak bisa dihapus.');
        }
        $user->delete();
        return redirect()->route('users.index')
                         ->with('status', 'User berhasil dihapus.');
    }
}
