<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JadwalKerjaController extends Controller
{
    // Kelola jadwal kerja
    public function getKelolaJadwalKerja()
    {
        //View barang_masuk
        return view('admin.jadwal_kerja.kelola_jadwal_kerja', [
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

    // Tampilan jadwal kerja
     public function getTampilanJadwalKerja()
    {
        //View barang_masuk
        return view('admin.jadwal_kerja.tampilan_jadwal_kerja', [
        'jadwalKerjas' => [],
    ]);
    }

    // STAFF CONTROLLER
     public function getJadwalKerjaStaff()
    {
        //View barang_masuk
        return view('staff.jadwal_kerja.jadwal_kerja_staff', [
        'jadwalKerjas' => [],
    ]);
    }   
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
