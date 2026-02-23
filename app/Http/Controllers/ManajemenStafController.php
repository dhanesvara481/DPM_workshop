<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ManajemenStafController extends Controller
{
    /**
     * Tampilan daftar semua staf (role = staff).
     * Kirim Eloquent collection langsung — view akses via $staf->status, $staf->role, dst.
     */
    public function getTampilanManajemenStaf()
    {
        $stafs = User::where('role', 'staff')
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view('admin.manajemen_staf.tampilan_manajemen_staf', compact('stafs'));
    }

    /**
     * Form tambah staf.
     */
    public function getTambahStaf()
    {
        return view('admin.manajemen_staf.tambah_staf');
    }

    /**
     * Simpan staf baru ke DB.
     */
    public function simpanStaf(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:20|unique:user,username',
            'email'    => 'required|email|max:100|unique:user,email',
            'kontak'   => 'required|digits_between:6,12',
            'password' => 'required|min:6',
            'catatan'  => 'nullable|string|max:255',
        ]);

        User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'kontak'   => $request->kontak,
            'password' => Hash::make($request->password),
            'role'     => 'staff',
            'status'   => 'aktif',
            'catatan'  => $request->catatan,
        ]);

        return redirect()->route('tampilan_manajemen_staf')
                         ->with('success', "Staf {$request->username} berhasil ditambahkan.");
    }

    /**
     * Form ubah staf — kirim object Eloquent $staf ke view.
     */
    public function getUbahStaf($id)
    {
        $staf = User::findOrFail($id);

        return view('admin.manajemen_staf.ubah_staf', compact('staf'));
    }

    /**
     * Update data staf.
     */
    public function updateStaf(Request $request, $id)
    {
        $staf = User::findOrFail($id);

        $request->validate([
            'username' => "required|string|max:20|unique:user,username,{$id},user_id",
            'email'    => "required|email|max:100|unique:user,email,{$id},user_id",
            'kontak'   => 'required|digits_between:6,12',
            'password' => 'nullable|min:6',
            'catatan'  => 'nullable|string|max:255',
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

        $staf->update($data);

        return redirect()->route('tampilan_manajemen_staf')
                         ->with('success', "Data staf {$staf->username} berhasil diperbarui.");
    }

    /**
     * Detail staf (opsional — saat ini detail ditampilkan via modal di tampilan utama).
     */
    public function getDetailStaf($id)
    {
        $staf = User::findOrFail($id);

        return view('admin.manajemen_staf.detail_staf', compact('staf'));
    }

    /**
     * Toggle status aktif <-> nonaktif.
     * Membaca & menulis kolom status (enum 'aktif'|'nonaktif') di tabel user.
     */
    public function toggleStatus($id)
    {
        $staf = User::findOrFail($id);

        // Flip status sesuai nilai enum di DB
        $staf->status = $staf->status === 'aktif' ? 'nonaktif' : 'aktif';
        $staf->save();

        $label = $staf->status === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('tampilan_manajemen_staf')
                         ->with('success', "Staf {$staf->username} berhasil {$label}.");
    }
}