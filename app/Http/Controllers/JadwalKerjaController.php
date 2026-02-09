<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JadwalKerjaController extends Controller
{
    public function getJadwalKerja()
    {
        //View barang_masuk
        return view('admin.jadwal_kerja.tampilan_jadwal_kerja', [
        'jadwalKerjas' => [],
    ]);
    }

    // Tambah jadwal kerja
    public function getTambahJadwalKerja()
    {
        //View barang_masuk
        return view('admin.jadwal_kerja.tambah_jadwal_kerja', [
        'jadwalKerjas' => [],
    ]);
    }

    // public function create()
    // {
    //     $users = User::orderBy('name')->get(['id','name']); // atau username
    //     return view('admin.jadwal_kerja.tambah_jadwal_kerja', compact('users'));
    // }

    //     $data = $request->validate([
    // 'user_id' => 'required|exists:users,id',
    // 'tanggal' => 'required|date',
    // 'jam_mulai' => 'required',
    // 'jam_selesai' => 'required',
    // // dst...
    // ]);
    // JadwalKerja::create($data);

    // tambah jadwal kerja End

    // Ubah jadwal kerja
    public function getUbahJadwalKerja()
    {
        //View barang_masuk
        return view('admin.jadwal_kerja.ubah_jadwal_kerja', [
        'jadwalKerjas' => [],
    ]);
    }
    // Ubah Jadwal kerja End

    // hapus jadwal kerja
    public function getHapusJadwalKerja()
    {
        //View barang_masuk
        return view('admin.jadwal_kerja.hapus_jadwal_kerja', [
        'jadwalKerjas' => [],
    ]);
    }
    // hapus jadwal kerja End

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
