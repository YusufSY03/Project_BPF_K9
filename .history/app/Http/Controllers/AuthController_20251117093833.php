<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Untuk memanggil model User
use Illuminate\Support\Facades\Hash; // Untuk enkripsi password
use Illuminate\Support\Facades\Auth; // Untuk login
use Illuminate\Validation\Rules; // Untuk validasi
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

        return view('auth.login');
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
    /**
     * Menampilkan halaman/view formulir registrasi.
     */
    public function showRegisterForm()
    {
        // Mirip dengan showLogin, jika sudah login, lempar ke home
        if (Auth::check()) {
             return redirect()->route('home');
        }

        return view('auth.register');
    }

    /**
     * Memproses data dari formulir registrasi.
     */
    public function register(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'], // 'unique:users' memastikan email belum terdaftar
            'password' => ['required', 'confirmed', Rules\Password::defaults()], // 'confirmed' mencocokkan dengan 'password_confirmation'
        ]);

        // 2. Buat pengguna baru di database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password DI-HASH (enkripsi)
            // 'role' akan kita tambahkan di langkah berikutnya
        ]);

        // 3. Login otomatis pengguna yang baru daftar
        Auth::login($user);

        // 4. Arahkan ke halaman home dengan pesan sukses
        return redirect()->route('home')->with('status', 'Akun Anda berhasil dibuat dan Anda telah login.');
    }
}

