<?php

namespace App.Http.Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLogin()
    {
        // Jika sudah login, lempar ke home.
        // Logika role akan kita urus nanti pakai middleware.
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Memproses percobaan login.
     * INI ADALAH FUNGSI YANG KITA UBAH TOTAL.
     */
    public function login(Request $request)
    {
        // 1. Validasi input (sekarang menggunakan email)
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // 2. Coba lakukan autentikasi (login)
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Jika berhasil
            $request->session()->regenerate();

            // PENTING:
            // Saat ini kita arahkan semua login ke 'home'.
            // Di langkah selanjutnya, setelah kita punya 'role',
            // kita akan ubah ini untuk redirect ke 'admin' atau 'owner'.
            
            return redirect()->route('home')->with('status', 'Berhasil login.');
        }

        // 3. Jika gagal
        return back()
            ->withErrors(['email' => 'Email atau password salah.']) // Ganti error ke 'email'
            ->withInput($request->only('email')); // Kembalikan input email saja
    }

    /**
     * Memproses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Menggunakan helper Auth

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Anda telah logout.');
    }

    // ===============================================
    // FUNGSI REGISTRASI DARI LANGKAH 2 (Tetap ada)
    // ===============================================

    /**
     * Menampilkan halaman/view formulir registrasi.
     */
    public function showRegisterForm()
    {
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Buat pengguna baru di database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'role' akan kita set default 'user' di langkah berikutnya
        ]);

        // 3. Login otomatis pengguna yang baru daftar
        Auth::login($user);

        // 4. Arahkan ke halaman home dengan pesan sukses
        return redirect()->route('home')->with('status', 'Akun Anda berhasil dibuat dan Anda telah login.');
    }
}