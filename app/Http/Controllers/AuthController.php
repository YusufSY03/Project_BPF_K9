<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Laravel\Socialite\Facades\Socialite;

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

        // ...
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Jika berhasil
            $request->session()->regenerate();

            // -- PERUBAHAN DIMULAI DI SINI --
            // Cek role user yang baru login
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin')->with('status', 'Berhasil login sebagai Admin.');
            } elseif ($user->role === 'owner') {
                return redirect()->route('owner')->with('status', 'Berhasil login sebagai Owner.');
            } else {
                // Untuk 'user' biasa
                return redirect()->route('home')->with('status', 'Berhasil login.');
            }
            // -- PERUBAHAN SELESAI --
        }
        // ...

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

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }


    public function handleGoogleCallback()
    {
        try {
            // 1. Ambil data dari Google
            $googleUser = Socialite::driver('google')->stateless()->user();

            // 2. Cari apakah user dengan email ini sudah ada di database?
            $findUser = User::where('email', $googleUser->email)->first();

            if ($findUser) {
                // KASUS A: User sudah pernah daftar sebelumnya
                // Langsung login-kan saja
                Auth::login($findUser);
                return redirect()->route('home')->with('status', 'Berhasil login kembali!');
            } else {
                // KASUS B: User baru (belum ada di database)
                // Kita buatkan akun baru secara otomatis
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make('password_google_dummy_123'), // Password acak (karena login via google)
                    'role' => 'user' // Sesuaikan role default di aplikasimu (misal: 'user' atau 'customer')
                ]);

                // Langsung login-kan user baru tersebut
                Auth::login($newUser);
                return redirect()->route('home')->with('status', 'Selamat datang! Akun Anda berhasil dibuat.');
            }
        } catch (\Exception $e) {
            // PENTING: Balikin ke sini (Redirect) supaya user tidak lihat layar hitam kalau error
            return redirect()->route('login')->with('error', 'Gagal login dengan Google. Silahkan coba lagi.');
        }
    }
}
