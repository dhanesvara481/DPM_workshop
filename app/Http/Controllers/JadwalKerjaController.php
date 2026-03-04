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
        $users = User::aktif()->orderBy('username')->get(['user_id', 'username', 'role']);

        $weekParam = $request->query('week');

        if ($weekParam && preg_match('/^(\d{4})-W(\d{2})$/', $weekParam, $m)) {
            $year   = (int) $m[1];
            $week   = (int) $m[2];
            $monday = new \DateTime();
            $monday->setISODate($year, $week, 1);
        } else {
            $monday = new \DateTime();
            $monday->modify('monday this week');
            $weekParam = $monday->format("Y-\\WW");
        }

        $sunday = clone $monday;
        $sunday->modify('+6 days');

        $existingRaw = \App\Models\JadwalKerja::whereBetween('tanggal_kerja', [
                $monday->format('Y-m-d'),
                $sunday->format('Y-m-d'),
            ])
            ->orderBy('tanggal_kerja')
            ->orderBy('jam_mulai')
            ->get(['jadwal_id', 'user_id', 'tanggal_kerja', 'waktu_shift', 'jam_mulai', 'jam_selesai', 'status', 'deskripsi']);

        $existingByDate = [];
        foreach ($existingRaw as $j) {
            $dateKey = $j->tanggal_kerja->format('Y-m-d');
            $existingByDate[$dateKey][] = [
                'jadwal_id'   => $j->jadwal_id,
                'user_id'     => $j->user_id,
                'waktu_shift' => $j->waktu_shift,
                'jam_mulai'   => $j->jam_mulai   ? substr($j->jam_mulai,   0, 5) : '',
                'jam_selesai' => $j->jam_selesai ? substr($j->jam_selesai, 0, 5) : '',
                'status'      => $j->status,
                'deskripsi'   => $j->deskripsi ?? '',
            ];
        }

        if ($request->ajax()) {
            return response()->json(['existingByDate' => $existingByDate]);
        }

        return view('admin.jadwal_kerja.tambah_jadwal_kerja', [
            'users'          => $users,
            'authUser'       => auth()->user(),
            'existingByDate' => $existingByDate,
            'weekParam'      => $weekParam,
        ]);
    }

    public function simpanJadwalKerja(Request $request)
    {
        $jadwalInput = $request->input('jadwal', []);

        if (empty($jadwalInput)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['days' => 'Pilih minimal 1 hari.']);
        }

        $dayKeys  = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
        $dayLabel = [
            'senin'  => 'Senin',  'selasa' => 'Selasa', 'rabu'   => 'Rabu',
            'kamis'  => 'Kamis',  'jumat'  => 'Jumat',  'sabtu'  => 'Sabtu',
            'minggu' => 'Minggu',
        ];

        $errors = [];
        $toSave = [];

        foreach ($dayKeys as $key) {
            if (!isset($jadwalInput[$key])) continue;

            $agendas = $jadwalInput[$key];
            if (!is_array($agendas) || empty($agendas)) continue;

            // Track user_id Aktif yang sudah muncul di hari ini (1 user = 1 Aktif/hari)
            $seenAktifUsers = [];

            foreach ($agendas as $i => $item) {
                $status  = $item['status'] ?? '';
                $isTutup   = $status === 'Tutup';
                $isCatatan = $status === 'Catatan';
                $isAktif   = $status === 'Aktif';
                $label     = $dayLabel[$key] . ' agenda ke-' . ($i + 1);
                $prefix    = "jadwal.{$key}.{$i}";
                $itemErrors = [];

                // ── Validasi field wajib ──────────────────────────────────
                if (empty($item['tanggal_kerja'])) {
                    $itemErrors["{$prefix}.tanggal_kerja"] = "Tanggal tidak ditemukan untuk {$label}.";
                }
                if (empty($item['status'])) {
                    $itemErrors["{$prefix}.status"] = "Status wajib dipilih untuk {$label}.";
                }

                // Catatan & Tutup: user di-force ke auth()->id() di sini (tidak perlu dari form)
                if ($isAktif) {
                    if (empty($item['user_id'])) {
                        $itemErrors["{$prefix}.user_id"] = "Nama wajib dipilih untuk {$label}.";
                    }
                    if (empty($item['waktu_shift'])) {
                        $itemErrors["{$prefix}.waktu_shift"] = "Waktu shift wajib diisi untuk {$label}.";
                    }
                    if (empty($item['jam_mulai'])) {
                        $itemErrors["{$prefix}.jam_mulai"] = "Jam mulai wajib diisi untuk {$label}.";
                    }
                    if (empty($item['jam_selesai'])) {
                        $itemErrors["{$prefix}.jam_selesai"] = "Jam selesai wajib diisi untuk {$label}.";
                    }
                    if (!empty($item['jam_mulai']) && !empty($item['jam_selesai'])) {
                        if ($item['jam_selesai'] <= $item['jam_mulai']) {
                            $itemErrors["{$prefix}.jam_selesai"] = "Jam selesai harus setelah jam mulai untuk {$label}.";
                        }
                    }
                }

                // ── Validasi user exists & aktif (hanya untuk Aktif) ─────
                if ($isAktif && !empty($item['user_id'])) {
                    $userExists = \App\Models\User::aktif()
                        ->where('user_id', $item['user_id'])
                        ->exists();
                    if (!$userExists) {
                        $itemErrors["{$prefix}.user_id"] = "User tidak valid atau tidak aktif untuk {$label}.";
                    }
                }

                // ── Duplikat: hanya cek untuk status Aktif ───────────────
                if ($isAktif && empty($itemErrors) && !empty($item['user_id'])) {
                    // Cek duplikat dalam input yang sama
                    if (isset($seenAktifUsers[$item['user_id']])) {
                        $itemErrors["{$prefix}.user_id"] =
                            "User sudah ada di {$seenAktifUsers[$item['user_id']]}. Satu user hanya boleh 1 shift Aktif per hari.";
                    } else {
                        $seenAktifUsers[$item['user_id']] = $label;
                    }

                    // Cek duplikat di DB (hanya Aktif)
                    if (empty($itemErrors)) {
                        $alreadyExists = \App\Models\JadwalKerja::where('user_id', $item['user_id'])
                            ->whereDate('tanggal_kerja', $item['tanggal_kerja'])
                            ->where('status', 'Aktif')
                            ->exists();
                        if ($alreadyExists) {
                            $itemErrors["{$prefix}.user_id"] =
                                "User ini sudah punya shift Aktif di tanggal tersebut ({$label}).";
                        }
                    }
                }

                if (!empty($itemErrors)) {
                    $errors = array_merge($errors, $itemErrors);
                } else {
                    $toSave[] = [
                        'day'  => $key,
                        'idx'  => $i,
                        'item' => $item,
                    ];
                }
            }
        }

        if (!empty($errors)) {
            return redirect()->back()
                ->withInput()
                ->withErrors($errors);
        }

        $saved = 0;
        $authUserId = auth()->id();

        foreach ($toSave as $entry) {
            $item      = $entry['item'];
            $status    = $item['status'] ?? 'Aktif';
            $isTutup   = $status === 'Tutup';
            $isCatatan = $status === 'Catatan';
            $tanggal   = $item['tanggal_kerja'];

            // Tutup: simpan langsung, jadwal lain tidak dihapus (hanya disembunyikan di display)
            \App\Models\JadwalKerja::create([
                'user_id'       => ($isTutup || $isCatatan) ? $authUserId : $item['user_id'],
                'tanggal_kerja' => $tanggal,
                'waktu_shift'   => $isTutup ? null : ($item['waktu_shift'] ?? null),
                'jam_mulai'     => $isTutup ? null : ($item['jam_mulai']   ?? null),
                'jam_selesai'   => $isTutup ? null : ($item['jam_selesai'] ?? null),
                'deskripsi'     => $isTutup ? null : ($item['deskripsi']   ?? null),
                'status'        => $status,
            ]);
            $saved++;


        }

        return redirect()->route('kelola_jadwal_kerja')
            ->with('success', "{$saved} agenda jadwal berhasil ditambahkan.");
    }

    // ─── Ubah ────────────────────────────────────────────────────────────────

    public function getUbahJadwalKerja(Request $request)
    {
        $date = $request->query('date');

        $jadwalKerjas = JadwalKerja::with('user')
            ->when($date, fn($q) => $q->whereDate('tanggal_kerja', $date))
            ->orderBy('jam_mulai')
            ->get();

        $users = User::aktif()
            ->orderBy('username')
            ->get(['user_id', 'username', 'role']);

        return view('admin.jadwal_kerja.ubah_jadwal_kerja',
            compact('jadwalKerjas', 'users', 'date') + ['authUser' => auth()->user()]
        );
    }

    public function perbaruiJadwalKerja(Request $request, $id)
    {
        $jadwal    = JadwalKerja::findOrFail($id);
        $isTutup   = $request->status === 'Tutup';
        $isCatatan = $request->status === 'Catatan';
        $isAktif   = $request->status === 'Aktif';
        $authUserId = auth()->id();

        $messages = [
            'user_id.required'     => 'User wajib dipilih.',
            'user_id.exists'       => 'User yang dipilih tidak valid.',
            'waktu_shift.required' => 'Waktu shift wajib diisi.',
            'jam_mulai.required'   => 'Jam mulai wajib diisi.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'jam_selesai.after'    => 'Jam selesai harus lebih besar dari jam mulai.',
        ];

        // User hanya wajib divalidasi dari form untuk status Aktif
        // Catatan & Tutup: user di-force ke auth()->id()
        $data = $request->validate([
            'tanggal_kerja' => 'required|date',
            'user_id'       => $isAktif ? 'required|exists:user,user_id' : 'nullable',
            'waktu_shift'   => ($isTutup || $isCatatan) ? 'nullable|in:Pagi,Siang,Sore,Malam' : 'required|in:Pagi,Siang,Sore,Malam',
            'jam_mulai'     => ($isTutup || $isCatatan) ? 'nullable|date_format:H:i' : 'required|date_format:H:i',
            'jam_selesai'   => ($isTutup || $isCatatan) ? 'nullable|date_format:H:i' : 'required|date_format:H:i|after:jam_mulai',
            'deskripsi'     => 'nullable|string|max:100',
            'status'        => 'required|in:Aktif,Catatan,Tutup',
        ], $messages);

        // ← TAMBAH BARIS INI:
        $data['user_id'] = $data['user_id'] ?? $authUserId;

        // Validasi duplikat Aktif (boleh jika jadwal ini sendiri)
        if ($isAktif) {
            $duplicate = \App\Models\JadwalKerja::where('user_id', $data['user_id'])
                ->whereDate('tanggal_kerja', $data['tanggal_kerja'])
                ->where('status', 'Aktif')
                ->where('jadwal_id', '!=', $id)
                ->exists();
            if ($duplicate) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['user_id' => 'User ini sudah punya shift Aktif di tanggal tersebut.']);
            }
        }

        // Force user untuk Catatan & Tutup
        if ($isCatatan || $isTutup) {
            $data['user_id'] = $authUserId;
        }

        // Tutup: jadwal lain TIDAK dihapus, hanya disembunyikan di display
        // Bersihkan field yang tidak relevan
        if ($isTutup) {
            $data['waktu_shift'] = null;
            $data['jam_mulai']   = null;
            $data['jam_selesai'] = null;
            $data['deskripsi']   = null;
        }

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

    // ─── Tampilan (view-only) ────────────────────────────────────────────────

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