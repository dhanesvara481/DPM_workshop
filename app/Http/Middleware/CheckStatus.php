<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user     = Auth::user();
            $routeRole = $request->route()?->getAction('role');

            // Blokir user nonaktif
            if ($user->status === 'nonaktif') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Akun Anda telah dinonaktifkan. Hubungi administrator.');
            }

            // Cek role jika route memiliki role yang ditentukan
            if ($routeRole && $user->role !== $routeRole) {
                return $user->role === 'admin'
                    ? redirect()->route('tampilan_dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.')
                    : redirect()->route('tampilan_dashboard_staff')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
        }

        return $next($request);
    }
}