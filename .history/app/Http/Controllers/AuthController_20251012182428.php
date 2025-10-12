<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Jika sudah login, arahkan langsung sesuai role
        if (session('role') === 'admin') {
            return redirect()->route('admin');
        }
        if (session('role') === 'user') {
            return redirect()->route('home');
        }

        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        // Login Admin: username=admin, password=admin1
        if ($username === 'admin' && $password === 'admin1') {
            session([
                'role' => 'admin',
                'user_name' => 'Administrator',
            ]);

            return redirect()->route('admin')->with('status', 'Berhasil login sebagai Admin.');
        }
        if ($username === 'owner' && $password === 'owner1') {
        session([
            'role' => 'owner',
            'user_name' => 'Owner',
        ]);

        return redirect()->route('owner')->with('status', 'Berhasil login sebagai Owner.');
    }

        // Login User: username=nohp, password=nohp (081270726389)
        if ($username === '081270726389' && $password === '081270726389') {
            session([
                'role' => 'user',
                'user_name' => 'User 081270726389',
            ]);

            return redirect()->route('home')->with('status', 'Berhasil login sebagai User.');
        }

        return back()
            ->withErrors(['auth' => 'Username atau password salah.'])
            ->withInput();
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->flush();

        return redirect()->route('login')->with('status', 'Anda telah logout.');
    }
}
