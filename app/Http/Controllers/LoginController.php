<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function validasiLogin()
    {
        return view('admin.login');
    }

    public function simpanData(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Jika kamu pakai email, ganti 'username' => 'email' di attempt.
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']], true)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()
            ->withInput($request->only('username'))
            ->with('error', 'Username atau password salah.');
    }
}
