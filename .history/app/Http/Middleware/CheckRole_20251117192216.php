<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  array<string>  $roles  // <-- Ini versi yang benar
     */
    public function handle(Request $request, Closure $next, ...$roles): Response // <-- Menggunakan '...'
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('home')->withErrors('Anda harus login terlebih dahulu.');
        }

        // 2. Cek apakah role user ADA DI DALAM array $roles
        if (!in_array(Auth::user()->role, $roles)) {
            // 3. Jika tidak, lempar ke halaman home
            return redirect('home')->withErrors('Anda tidak memiliki izin untuk mengakses halaman tersebut.');
        }

        // 4. Jika rolenya diizinkan, izinkan request lanjut
        return $next($request);
    }
}