<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: CheckRole
 *
 * Memastikan user yang sudah login memiliki role yang benar
 * sebelum mengakses halaman tertentu.
 *
 * Penggunaan di route: ->middleware('role:admin')
 *                      ->middleware('role:wisatawan')
 *                      ->middleware('role:mitra_faskes')
 */
class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->session()->get('auth_user');

        if (!$user) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!in_array($user['role'], $roles)) {
            // Redirect ke dashboard sesuai role user yang sebenarnya
            return match($user['role']) {
                'admin'         => redirect('/dashboard/admin'),
                'mitra_faskes'  => redirect('/dashboard/faskes'),
                'wisatawan'     => redirect('/dashboard/wisatawan'),
                default         => redirect('/'),
            };
        }

        return $next($request);
    }
}
