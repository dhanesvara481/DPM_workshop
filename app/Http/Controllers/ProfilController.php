<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    // ── Tampilan Profil (Admin) ──────────────────────────────────────────────

    public function getTampilanProfil()
    {
        $user = Auth::user();
        return view('admin.profil.tampilan_profil', compact('user'));
    }

    // ── Form Edit Profil (Admin) ─────────────────────────────────────────────

    public function getEditProfil()
    {
        $user = Auth::user();
        return view('admin.profil.ubah_profil', compact('user'));
    }

    // ── Simpan Perubahan Profil (Admin) ──────────────────────────────────────

    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => "required|string|max:20|unique:user,username,{$user->user_id},user_id",
            'email'    => "required|email|max:100|unique:user,email,{$user->user_id},user_id",
            'kontak'   => 'required|digits_between:6,12',
            'catatan'  => 'nullable|string|max:255',
            'password' => 'nullable|min:6|confirmed',
        ], [
            'username.required'   => 'Username wajib diisi.',
            'username.unique'     => 'Username sudah digunakan.',
            'email.required'      => 'Email wajib diisi.',
            'email.unique'        => 'Email sudah digunakan.',
            'kontak.required'     => 'Nomor kontak wajib diisi.',
            'kontak.digits_between' => 'Nomor kontak harus 6–12 digit.',
            'password.min'        => 'Password minimal 6 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
        ]);

        $data = [
            'username' => $request->username,
            'email'    => $request->email,
            'kontak'   => $request->kontak,
            'catatan'  => $request->catatan,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()
            ->route('tampilan_profil')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    // ─── STAFF: Tampilan Profil (read-only) ──────────────────────────────────
    // Staff TIDAK bisa edit apapun. Tidak ada method edit/update untuk staff.

    public function getTampilanProfilStaff()
    {
        $user = Auth::user();
        return view('staff.profil.tampilan_profil_staff', compact('user'));
    }
}