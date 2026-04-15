<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: AuthSession
 *
 * Memverifikasi bahwa user sudah login (ada data di session).
 * Digunakan untuk melindungi semua rute dashboard.
 */
class AuthSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('auth_user')) {
            return redirect('/login')->with('error', 'Anda harus login untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
