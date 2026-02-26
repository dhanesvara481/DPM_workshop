<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JadwalKerja;
use App\Models\User;
use Illuminate\Http\Request;

class JadwalKerjaController extends Controller
{
    // ─── Kelola (Kalender) ───────────────────────────────────────────────────

    public function getKelolaJadwalKerja()
    {
        $rawEvents = JadwalKerja::with('user')
            ->orderBy('tanggal_kerja')
            ->orderBy('jam_mulai')
            ->get();

        $events = [];

        foreach ($rawEvents as $j) {
            $key = $j->tanggal_kerja->format('Y-m-d');

            $events[$key][] = [
                'id'     => $j->jadwal_id,
                'title'  => ($j->waktu_shift ?? 'Jadwal') . ' - ' . ($j->user->username ?? 'Staf'),
                'status' => strtolower($j->status),
                'time'   => substr($j->jam_mulai, 0, 5) . ' - ' . substr($j->jam_selesai, 0, 5),
                'desc'   => $j->deskripsi,
            ];
        }

        return view('admin.jadwal_kerja.kelola_jadwal_kerja', [
            'events'             => $events,
            'MAX_EVENTS_PER_DAY' => 4,
        ]);
    }

    // ─── Tambah ──────────────────────────────────────────────────────────────

    public function getTambahJadwalKerja(Request $request)
    {
        $users = User::orderBy('username')->get(['user_id', 'username', 'role']);

        return view('admin.jadwal_kerja.tambah_jadwal_kerja', [
            'users'        => $users,
            'selectedDate' => $request->query('date'),
            'authUser'     => auth()->user(), // ✅ kirim data admin yang login
        ]);
    }

    public function simpanJadwalKerja(Request $request)
    {
        $data = $request->validate([
            'user_id'       => 'required|exists:user,user_id',
            'tanggal_kerja' => 'required|date',
            'waktu_shift'   => 'required|in:Pagi,Siang,Sore,Malam',
            'jam_mulai'     => 'required|date_format:H:i',
            'jam_selesai'   => 'required|date_format:H:i|after:jam_mulai',
            'deskripsi'     => 'nullable|string|max:100',
            'status'        => 'required|in:Aktif,Catatan,Tutup',
        ]);

        JadwalKerja::create($data);

        return redirect()->route('kelola_jadwal_kerja')
                         ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    // ─── Ubah ────────────────────────────────────────────────────────────────

    public function getUbahJadwalKerja(Request $request)
    {
        $date = $request->query('date');

        $jadwalKerjas = JadwalKerja::with('user')
            ->when($date, fn($q) => $q->whereDate('tanggal_kerja', $date))
            ->orderBy('jam_mulai')
            ->get();

        $users = User::orderBy('username')->get(['user_id', 'username', 'role']); // ✅ ambil kolom role

        return view('admin.jadwal_kerja.ubah_jadwal_kerja',
            compact('jadwalKerjas', 'users', 'date') + ['authUser' => auth()->user()] // ✅ kirim authUser
        );
    }

    public function perbaruiJadwalKerja(Request $request, $id)
    {
        $jadwal = JadwalKerja::findOrFail($id);

        $data = $request->validate([
            'user_id'       => 'required|exists:user,user_id',
            'tanggal_kerja' => 'required|date',
            'waktu_shift'   => 'required|in:Pagi,Siang,Sore,Malam',
            'jam_mulai'     => 'required|date_format:H:i',
            'jam_selesai'   => 'required|date_format:H:i|after:jam_mulai',
            'deskripsi'     => 'nullable|string|max:100',
            'status'        => 'required|in:Aktif,Catatan,Tutup',
        ]);

        $jadwal->update($data);

        return redirect()->route('kelola_jadwal_kerja')
                         ->with('success', 'Jadwal berhasil diubah.');
    }

    // ─── Hapus ───────────────────────────────────────────────────────────────

    public function getHapusJadwalKerja(Request $request)
    {
        $date = $request->query('date');

        $jadwalKerjas = JadwalKerja::with('user')
            ->when($date, fn($q) => $q->whereDate('tanggal_kerja', $date))
            ->orderBy('jam_mulai')
            ->get();

        return view('admin.jadwal_kerja.hapus_jadwal_kerja',
            compact('jadwalKerjas', 'date')
        );
    }

    public function hapusJadwalKerja($id)
    {
        JadwalKerja::findOrFail($id)->delete();

        return redirect()->route('kelola_jadwal_kerja')
                         ->with('success', 'Jadwal berhasil dihapus.');
    }

    public function hapusBatch(Request $request)
    {
        $ids = collect($request->input('targets', []))
            ->filter(fn($t) => str_starts_with($t, 'event:'))
            ->map(fn($t) => (int) str_replace('event:', '', $t));

        JadwalKerja::whereIn('jadwal_id', $ids)->delete();

        return redirect()->route('kelola_jadwal_kerja')
                         ->with('success', count($ids) . ' jadwal berhasil dihapus.');
    }

    public function hapusSemuaTanggal(Request $request)
    {
        $date = $request->input('date');

        JadwalKerja::whereDate('tanggal_kerja', $date)->delete();

        return redirect()->route('kelola_jadwal_kerja')
                         ->with('success', 'Semua jadwal tanggal ' . $date . ' berhasil dihapus.');
    }

    // ─── Tampilan (view-only) ─────────────────────────────────────────────────

    public function getTampilanJadwalKerja()
    {
        $jadwalKerjas = JadwalKerja::with('user')
            ->orderBy('tanggal_kerja')
            ->orderBy('jam_mulai')
            ->get();

        return view('admin.jadwal_kerja.tampilan_jadwal_kerja',
            compact('jadwalKerjas')
        );
    }

    // ─── STAFF ───────────────────────────────────────────────────────────────

    public function getJadwalKerjaStaff()
    {
        $rawEvents = JadwalKerja::with('user')
            ->orderBy('tanggal_kerja')
            ->orderBy('jam_mulai')
            ->get();

        $events = [];

        foreach ($rawEvents as $j) {
            $key = $j->tanggal_kerja->format('Y-m-d');

            $events[$key][] = [
                'id'     => $j->jadwal_id,
                'title'  => ($j->waktu_shift ?? 'Jadwal') . ' - ' . ($j->user->username ?? 'Staf'),
                'status' => strtolower($j->status),
                'time'   => substr($j->jam_mulai, 0, 5) . ' - ' . substr($j->jam_selesai, 0, 5),
                'desc'   => $j->deskripsi,
            ];
        }

        return view('staff.jadwal_kerja.jadwal_kerja_staff', [
            'events'             => $events,
            'MAX_EVENTS_PER_DAY' => 4,
        ]);
    }

    public function destroy(string $id)
    {
        JadwalKerja::findOrFail($id)->delete();

        return redirect()->route('kelola_jadwal_kerja')
                         ->with('success', 'Jadwal berhasil dihapus.');
    }
}