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
 * Juga mengecek status aktif user di database secara real-time.
 */
class AuthSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('auth_user')) {
            return redirect('/login')->with('error', 'Anda harus login untuk mengakses halaman ini.');
        }

        $sessionUser = $request->session()->get('auth_user');
        $id   = $sessionUser['id'] ?? 0;
        $role = $sessionUser['role'] ?? '';

        // Skip check untuk Admin karena tidak ada di DB User/Mitra (hardcoded)
        if ($role === 'admin') {
            return $next($request);
        }

        // Cek status aktif & alasan di database secara real-time
        $isActive = true;
        $reason   = null;

        if ($role === 'wisatawan') {
            $user = \App\Models\User::find($id);
            if ($user) {
                $isActive = $user->is_active;
                $reason   = $user->blocking_reason;
            }
        } elseif (str_starts_with($role, 'mitra')) {
            $mitra = \App\Models\Mitra::find($id);
            if ($mitra) {
                $isActive = $mitra->is_active;
                $reason   = $mitra->blocking_reason;
            }
        }

        // Jika akun diblokir saat sedang login, paksa keluar
        if (!$isActive) {
            $request->session()->forget('auth_user');
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            $msg = "Akun Anda telah DIBLOKIR oleh Admin.";
            if ($reason) {
                $msg .= " Alasan: " . $reason;
            }
            $msg .= " Anda otomatis dikeluarkan dari sistem.";
            
            return redirect('/login')->with('error', $msg);
        }

        return $next($request);
    }
}
