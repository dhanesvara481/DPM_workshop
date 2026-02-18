<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Tampilan halaman login.
     */
    public function validasiLogin()
    {
        // Jika sudah login, langsung redirect ke dashboard
        if (Auth::check()) {
            return redirect()->route('tampilan_dashboard');
        }

        return view('admin.login');
    }

    /**
     * Proses login.
     */
    public function simpanData(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Coba autentikasi dengan username + password
        if (Auth::attempt(
            ['username' => $credentials['username'], 'password' => $credentials['password']],
            $request->boolean('remember')
        )) {
            // Cek status akun (aktif / nonaktif)
            if (Auth::user()->status === 'nonaktif') {
                Auth::logout();
                return back()
                    ->withInput($request->only('username'))
                    ->with('error', 'Akun Anda telah dinonaktifkan. Hubungi administrator.');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('tampilan_dashboard'));
        }

        return back()
            ->withInput($request->only('username'))
            ->with('error', 'Username atau password salah.');
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}