<?php

namespace App\Http\Controllers;

use App\Models\JadwalKerja;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Threshold status stok (harus sinkron dengan StokRealtimeController).
     *  - stok = 0          → Habis
     *  - stok > 0 && < 25  → Menipis
     *  - stok >= 25        → Aman
     */
    private const STOK_MIN = 25;

    // ── Tampilan Dashboard Admin ────────────────────────────────────────────

    public function getTampilanDashboard()
    {
        $chartMasuk  = $this->buildChartMasuk();
        $chartKeluar = $this->buildChartKeluar();
        $events      = $this->buildEvents();

        $MAX_EVENTS_PER_DAY = 4;

        // ── Ringkasan Stok ──────────────────────────────────────────────────
        // Tabel: barang | kolom stok: VARCHAR — di-CAST ke UNSIGNED agar aman

        $stockTotalItem = DB::table('barang')->count();

        // Stok Menipis: stok > 0 dan < 25
        $stockLow = DB::table('barang')
            ->whereRaw('CAST(stok AS UNSIGNED) > 0')
            ->whereRaw('CAST(stok AS UNSIGNED) < ?', [self::STOK_MIN])
            ->count();

        // Barang Habis: stok = 0
        $stockOut = DB::table('barang')
            ->whereRaw('CAST(stok AS UNSIGNED) = 0')
            ->count();

        // ── Ringkasan Transaksi ─────────────────────────────────────────────
        $txTodayAll = DB::table('invoice')
            ->whereDate('created_at', today())
            ->count();

        $txTotalAll = DB::table('invoice')->count();

        return view('admin.dashboard.tampilan_dashboard', compact(
            'chartMasuk',
            'chartKeluar',
            'events',
            'MAX_EVENTS_PER_DAY',
            'stockTotalItem',
            'stockLow',
            'stockOut',
            'txTodayAll',
            'txTotalAll',
        ));
    }

    // ── Tampilan Dashboard Staff ────────────────────────────────────────────

    public function getTampilanDashboardStaff()
    {
        // ── Ringkasan Stok (threshold sama dengan admin) ────────────────────
        $stockTotalItem = DB::table('barang')->count();

        // Stok Menipis: stok > 0 dan < 25
        $stockLow = DB::table('barang')
            ->whereRaw('CAST(stok AS UNSIGNED) > 0')
            ->whereRaw('CAST(stok AS UNSIGNED) < ?', [self::STOK_MIN])
            ->count();

        // Barang Habis: stok = 0
        $stockOut = DB::table('barang')
            ->whereRaw('CAST(stok AS UNSIGNED) = 0')
            ->count();

        // ── Ringkasan Transaksi ─────────────────────────────────────────────
        $userId = Auth::id();

        $txTodayAll = DB::table('invoice')
            ->whereDate('created_at', today())
            ->where('user_id', $userId)   // ← sesuaikan nama kolom
            ->count();
        
        $txTotalAll = DB::table('invoice')
            ->where('user_id', $userId)   // ← sesuaikan nama kolom
            ->count();

        // ── Events Jadwal ───────────────────────────────────────────────────
        $events             = $this->buildEvents();
        $MAX_EVENTS_PER_DAY = 4;

        return view('staff.dashboard.tampilan_dashboard_staff', compact(
            'stockTotalItem',
            'stockLow',
            'stockOut',
            'txTodayAll',
            'txTotalAll',
            'events',
            'MAX_EVENTS_PER_DAY',
        ));
    }

    // ── Chart Masuk ─────────────────────────────────────────────────────────

    private function buildChartMasuk(): array
    {
        return [
            '6m'   => $this->queryMasuk(6),
            '12m'  => $this->queryMasuk(12),
            'year' => $this->queryMasukKuartal(),
        ];
    }

    private function queryMasuk(int $months): array
    {
        $rows = DB::table('barang_masuk')
            ->selectRaw("DATE_FORMAT(tanggal_masuk, '%b') AS label,
                         DATE_FORMAT(tanggal_masuk, '%Y-%m') AS ym,
                         SUM(jumlah_masuk) AS total")
            ->where('tanggal_masuk', '>=', now()->subMonths($months)->startOfMonth())
            ->groupByRaw("DATE_FORMAT(tanggal_masuk, '%Y-%m'), DATE_FORMAT(tanggal_masuk, '%b')")
            ->orderBy('ym')
            ->get();

        return [
            'label'  => "{$months} bulan terakhir",
            'labels' => $rows->pluck('label')->toArray(),
            'masuk'  => $rows->pluck('total')->map(fn ($v) => (int) $v)->toArray(),
        ];
    }

    private function queryMasukKuartal(): array
    {
        $year = now()->year;
        $rows = DB::table('barang_masuk')
            ->selectRaw("CONCAT('Q', QUARTER(tanggal_masuk)) AS label,
                         QUARTER(tanggal_masuk) AS q,
                         SUM(jumlah_masuk) AS total")
            ->whereYear('tanggal_masuk', $year)
            ->groupByRaw("QUARTER(tanggal_masuk), CONCAT('Q', QUARTER(tanggal_masuk))")
            ->orderBy('q')
            ->get();

        $map    = $rows->keyBy('label');
        $labels = ['Q1', 'Q2', 'Q3', 'Q4'];
        $data   = array_map(fn ($l) => (int) ($map[$l]->total ?? 0), $labels);

        return [
            'label'  => 'Tahun ini (per kuartal)',
            'labels' => $labels,
            'masuk'  => $data,
        ];
    }

    // ── Chart Keluar ────────────────────────────────────────────────────────

    private function buildChartKeluar(): array
    {
        return [
            '6m'   => $this->queryKeluar(6),
            '12m'  => $this->queryKeluar(12),
            'year' => $this->queryKeluarKuartal(),
        ];
    }
    
    private function queryKeluar(int $months): array
    {
        $rows = DB::table('barang_keluar')
            ->selectRaw("DATE_FORMAT(tanggal_keluar, '%b') AS label,
                         DATE_FORMAT(tanggal_keluar, '%Y-%m') AS ym,
                         SUM(jumlah_keluar) AS total")
            ->where('tanggal_keluar', '>=', now()->subMonths($months)->startOfMonth())
            ->groupByRaw("DATE_FORMAT(tanggal_keluar, '%Y-%m'), DATE_FORMAT(tanggal_keluar, '%b')")
            ->orderBy('ym')
            ->get();

        return [
            'label'  => "{$months} bulan terakhir",
            'labels' => $rows->pluck('label')->toArray(),
            'keluar' => $rows->pluck('total')->map(fn ($v) => (int) $v)->toArray(),
        ];
    }
    
    private function queryKeluarKuartal(): array
    {
        $year = now()->year;
        $rows = DB::table('barang_keluar')
            ->selectRaw("CONCAT('Q', QUARTER(tanggal_keluar)) AS label,
                         QUARTER(tanggal_keluar) AS q,
                         SUM(jumlah_keluar) AS total")
            ->whereYear('tanggal_keluar', $year)
            ->groupByRaw("QUARTER(tanggal_keluar), CONCAT('Q', QUARTER(tanggal_keluar))")
            ->orderBy('q')
            ->get();

        $map    = $rows->keyBy('label');
        $labels = ['Q1', 'Q2', 'Q3', 'Q4'];
        $data   = array_map(fn ($l) => (int) ($map[$l]->total ?? 0), $labels);

        return [
            'label'  => 'Tahun ini (per kuartal)',
            'labels' => $labels,
            'keluar' => $data,
        ];
    }

    // ── Events Jadwal (dipakai admin & staff) ───────────────────────────────
    // Data jadwal kerja dari 30 hari ke belakang sampai 60 hari ke depan, diurutkan berdasarkan tanggal & jam.
    private function buildEvents(): array
    {
        try {
            $rows = JadwalKerja::with('user')
                ->whereBetween('tanggal_kerja', [
                    now()->subDays(30)->toDateString(),
                    now()->addDays(60)->toDateString(),
                ])
                ->orderBy('tanggal_kerja')
                ->orderBy('jam_mulai')
                ->get();

            $events = [];

            foreach ($rows as $row) {
                $key = $row->tanggal_kerja->toDateString();

                $time = '';
                if ($row->jam_mulai && $row->jam_selesai) {
                    $time = substr($row->jam_mulai, 0, 5) . ' - ' . substr($row->jam_selesai, 0, 5);
                } elseif ($row->jam_mulai) {
                    $time = substr($row->jam_mulai, 0, 5);
                }

                $events[$key][] = [
                    'id'     => $row->jadwal_id,
                    'title'  => ($row->waktu_shift ?? 'Jadwal') . ' - ' . ($row->user->username ?? 'Staf'),
                    'status' => strtolower($row->status ?? 'aktif'),
                    'time'   => $time,
                    'desc'   => $row->deskripsi ?? '',
                ];
            }

            return $events;

        } catch (\Exception $e) {
            return [];
        }
    }
}