<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Pastikan Auth di-import
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  // <-- Kita tambahkan parameter $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek apakah user sudah login DAN rolenya sesuai
        if (! Auth::check() || Auth::user()->role !== $role) {

            // 2. Jika tidak, lempar ke halaman home
            //    (atau bisa juga ke halaman login)
            return redirect('home')->withErrors('Anda tidak memiliki izin untuk mengakses halaman tersebut.');
        }

        // 3. Jika rolenya sesuai, izinkan request lanjut
        return $next($request);
    }
}
