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

            // ✅ Kalau status Tutup, jam tidak ditampilkan (null/kosong)
            $isTutup = strtolower($j->status) === 'tutup';

            $events[$key][] = [
                'id'     => $j->jadwal_id,
                'title'  => ($j->waktu_shift ?? 'Jadwal') . ' - ' . ($j->user->username ?? 'Staf'),
                'status' => strtolower($j->status),
                'time'   => $isTutup ? null : (substr($j->jam_mulai ?? '', 0, 5) . ' - ' . substr($j->jam_selesai ?? '', 0, 5)),
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
        $users = User::where('is_active', 1)
             ->orderBy('username')
             ->get(['user_id', 'username', 'role']);

        return view('admin.jadwal_kerja.tambah_jadwal_kerja', [
            'users'    => $users,
            'authUser' => auth()->user(),
        ]);
    }

    public function simpanJadwalKerja(Request $request)
    {
        // Ambil data per hari dari input jadwal[senin][...], jadwal[selasa][...], dst.
        $jadwalInput = $request->input('jadwal', []);

        if (empty($jadwalInput)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['days' => 'Pilih minimal 1 hari.']);
        }

        $dayKeys = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
        $errors  = [];
        $toSave  = [];

        foreach ($dayKeys as $key) {
            if (!isset($jadwalInput[$key])) continue;

            $item    = $jadwalInput[$key];
            $isTutup = ($item['status'] ?? '') === 'Tutup';
            $prefix  = "jadwal.{$key}";

            // Validasi per hari
            if (empty($item['user_id'])) {
                $errors["{$prefix}.user_id"] = "User wajib dipilih untuk hari " . ucfirst($key) . ".";
            }
            if (empty($item['tanggal_kerja'])) {
                $errors["{$prefix}.tanggal_kerja"] = "Tanggal tidak ditemukan untuk hari " . ucfirst($key) . ".";
            }
            if (empty($item['status'])) {
                $errors["{$prefix}.status"] = "Status wajib dipilih untuk hari " . ucfirst($key) . ".";
            }
            if (!$isTutup) {
                if (empty($item['waktu_shift'])) {
                    $errors["{$prefix}.waktu_shift"] = "Waktu shift wajib diisi untuk hari " . ucfirst($key) . ".";
                }
                if (empty($item['jam_mulai'])) {
                    $errors["{$prefix}.jam_mulai"] = "Jam mulai wajib diisi untuk hari " . ucfirst($key) . ".";
                }
                if (empty($item['jam_selesai'])) {
                    $errors["{$prefix}.jam_selesai"] = "Jam selesai wajib diisi untuk hari " . ucfirst($key) . ".";
                }
                if (!empty($item['jam_mulai']) && !empty($item['jam_selesai'])) {
                    if ($item['jam_selesai'] <= $item['jam_mulai']) {
                        $errors["{$prefix}.jam_selesai"] = "Jam selesai harus setelah jam mulai untuk hari " . ucfirst($key) . ".";
                    }
                }
            }

            // Validasi user exists
            if (!empty($item['user_id'])) {
                $userExists = \App\Models\User::where('user_id', $item['user_id'])->exists();
                if (!$userExists) {
                    $errors["{$prefix}.user_id"] = "User tidak valid untuk hari " . ucfirst($key) . ".";
                }
            }

            if (empty($errors)) {
                $toSave[$key] = $item;
            }
        }

        if (!empty($errors)) {
            return redirect()->back()
                ->withInput()
                ->withErrors($errors);
        }

        // Simpan semua record
        foreach ($toSave as $key => $item) {
            $isTutup = ($item['status'] ?? '') === 'Tutup';

            \App\Models\JadwalKerja::create([
                'user_id'       => $item['user_id'],
                'tanggal_kerja' => $item['tanggal_kerja'],
                'waktu_shift'   => $isTutup ? null : ($item['waktu_shift'] ?? null),
                'jam_mulai'     => $isTutup ? null : ($item['jam_mulai']   ?? null),
                'jam_selesai'   => $isTutup ? null : ($item['jam_selesai'] ?? null),
                'deskripsi'     => $isTutup ? null : ($item['deskripsi']   ?? null),
                'status'        => $item['status'],
            ]);
        }

        $count = count($toSave);

        return redirect()->route('kelola_jadwal_kerja')
            ->with('success', "{$count} jadwal berhasil ditambahkan.");
    }

    // ─── Ubah ────────────────────────────────────────────────────────────────

    public function getUbahJadwalKerja(Request $request)
    {
        $date = $request->query('date');

        $jadwalKerjas = JadwalKerja::with('user')
            ->when($date, fn($q) => $q->whereDate('tanggal_kerja', $date))
            ->orderBy('jam_mulai')
            ->get();

        $users = User::where('is_active', 1)
             ->orderBy('username')
             ->get(['user_id', 'username', 'role']);


        return view('admin.jadwal_kerja.ubah_jadwal_kerja',
            compact('jadwalKerjas', 'users', 'date') + ['authUser' => auth()->user()]
        );
    }

    public function perbaruiJadwalKerja(Request $request, $id)
    {
        $jadwal = JadwalKerja::findOrFail($id);

        // ✅ Field opsional saat status Tutup
        $isTutup = $request->status === 'Tutup';

        // custom messages in Bahasa Indonesia
        $messages = [
            'user_id.required'      => 'User wajib dipilih.',
            'user_id.exists'        => 'User yang dipilih tidak valid.',
            'waktu_shift.required'  => 'Waktu shift wajib diisi.',
            'jam_mulai.required'    => 'Jam mulai wajib diisi.',
            'jam_selesai.required'  => 'Jam selesai wajib diisi.',
            'jam_selesai.after'     => 'Jam selesai harus lebih besar (setelah) jam mulai.',
        ];

        $data = $request->validate([
            'user_id'       => 'required|exists:user,user_id',
            'tanggal_kerja' => 'required|date',
            'waktu_shift'   => $isTutup ? 'nullable|in:Pagi,Siang,Sore,Malam' : 'required|in:Pagi,Siang,Sore,Malam',
            'jam_mulai'     => $isTutup ? 'nullable|date_format:H:i' : 'required|date_format:H:i',
            'jam_selesai'   => $isTutup ? 'nullable|date_format:H:i' : 'required|date_format:H:i|after:jam_mulai',
            'deskripsi'     => 'nullable|string|max:100',
            'status'        => 'required|in:Aktif,Catatan,Tutup',
        ], $messages);

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

        // kembali ke halaman hapus agar pesan muncul di situ
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    public function hapusBatch(Request $request)
    {
        $ids = collect($request->input('targets', []))
            ->filter(fn($t) => str_starts_with($t, 'event:'))
            ->map(fn($t) => (int) str_replace('event:', '', $t));

        JadwalKerja::whereIn('jadwal_id', $ids)->delete();

        $date = $request->input('date');
        return redirect()->route('hapus_jadwal_kerja', ['date' => $date])
                         ->with('success', count($ids) . ' jadwal berhasil dihapus.');
    }

    public function hapusSemuaTanggal(Request $request)
    {
        $date = $request->input('date');

        JadwalKerja::whereDate('tanggal_kerja', $date)->delete();

        return redirect()->route('hapus_jadwal_kerja', ['date' => $date])
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
            $key     = $j->tanggal_kerja->format('Y-m-d');
            $isTutup = strtolower($j->status) === 'tutup';

            $events[$key][] = [
                'id'     => $j->jadwal_id,
                'title'  => ($j->waktu_shift ?? 'Jadwal') . ' - ' . ($j->user->username ?? 'Staf'),
                'status' => strtolower($j->status),
                'time'   => $isTutup ? null : (substr($j->jam_mulai ?? '', 0, 5) . ' - ' . substr($j->jam_selesai ?? '', 0, 5)),
                'desc'   => $j->deskripsi,
            ];
        }

        return view('staff.jadwal_kerja.jadwal_kerja_staff', [
            'events'             => $events,
            'MAX_EVENTS_PER_DAY' => 4,
        ]);
    }
}