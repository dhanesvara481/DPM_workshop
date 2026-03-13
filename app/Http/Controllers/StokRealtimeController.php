<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class StokRealtimeController extends Controller
{
    /**
     * Threshold global status stok.
     *  - stok = 0          → Habis
     *  - stok > 0 && < 25  → Menipis
     *  - stok >= 25        → Aman
     */
    private const STOK_MIN = 25;

    // ── Admin ────────────────────────────────────────────────────────────────

    public function getStokRealtime(Request $request)
    {
        $q      = $request->input('q');
        $status = $request->input('status');

        $query = Barang::query()->orderBy('barang_id', 'asc');

        // Filter pencarian kode / nama
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $like = "%{$q}%";
                $sub->where('kode_barang', 'like', $like)
                    ->orWhere('nama_barang', 'like', $like);
            });
        }

        // Filter status stok
        if ($status === 'Aman') {
            $query->whereRaw('CAST(stok AS UNSIGNED) >= ?', [self::STOK_MIN]);
        } elseif ($status === 'Menipis') {
            $query->whereRaw('CAST(stok AS UNSIGNED) > 0')
                  ->whereRaw('CAST(stok AS UNSIGNED) < ?', [self::STOK_MIN]);
        } elseif ($status === 'Habis') {
            $query->whereRaw('CAST(stok AS UNSIGNED) = 0');
        }

        $barangs = $query->paginate(10)->withQueryString();

        // ── Summary cards — selalu dari SEMUA barang (tidak terpengaruh filter) ──
        $allBarangs   = Barang::all();
        $summaryTotal = $allBarangs->count();
        $summarySum   = $allBarangs->sum(fn ($b) => (int) $b->stok);
        $summaryLow   = $allBarangs->filter(fn ($b) => (int) $b->stok > 0 && (int) $b->stok < self::STOK_MIN)->count();
        $summaryOut   = $allBarangs->filter(fn ($b) => (int) $b->stok <= 0)->count();

        return view('admin.stok_realtime', compact(
            'barangs',
            'q',
            'status',
            'summaryTotal',
            'summarySum',
            'summaryLow',
            'summaryOut',
        ));
    }

    public function print(Request $request)
    {
        $barangs = Barang::orderBy('barang_id', 'asc')->get();

        return view('admin.print.stokrealtime', compact('barangs'));
    }

    // ── Staff ────────────────────────────────────────────────────────────────

    public function getStokRealtimeStaff(Request $request)
    {
        $q      = $request->input('q');
        $status = $request->input('status');

        $query = Barang::query()->orderBy('barang_id', 'asc');

        // Filter pencarian kode / nama
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $like = "%{$q}%";
                $sub->where('kode_barang', 'like', $like)
                    ->orWhere('nama_barang', 'like', $like);
            });
        }

        // Filter status stok
        if ($status === 'Aman') {
            $query->whereRaw('CAST(stok AS UNSIGNED) >= ?', [self::STOK_MIN]);
        } elseif ($status === 'Menipis') {
            $query->whereRaw('CAST(stok AS UNSIGNED) > 0')
                  ->whereRaw('CAST(stok AS UNSIGNED) < ?', [self::STOK_MIN]);
        } elseif ($status === 'Habis') {
            $query->whereRaw('CAST(stok AS UNSIGNED) = 0');
        }

        $barangs = $query->paginate(10)->withQueryString();

        // ── Summary cards — selalu dari SEMUA barang ──
        $allBarangs   = Barang::all();
        $summaryTotal = $allBarangs->count();
        $summarySum   = $allBarangs->sum(fn ($b) => (int) $b->stok);
        $summaryLow   = $allBarangs->filter(fn ($b) => (int) $b->stok > 0 && (int) $b->stok < self::STOK_MIN)->count();
        $summaryOut   = $allBarangs->filter(fn ($b) => (int) $b->stok <= 0)->count();

        return view('staff.stok_realtime.stok_realtime_staff', compact(
            'barangs',
            'q',
            'status',
            'summaryTotal',
            'summarySum',
            'summaryLow',
            'summaryOut',
        ));
    }

    public function printStaff()
    {
        $barangs = Barang::orderBy('barang_id', 'asc')->get();

        return view('staff.stok_realtime.print_stokrealtime_staff', compact('barangs'));
    }

    // ── Helper statis (bisa dipakai controller lain via import) ──────────────

    /**
     * Kembalikan label dan class badge berdasarkan jumlah stok.
     *
     * @param  int  $stok
     * @return array{ label: string, class: string }
     */
    public static function tentukanStatusStok(int $stok): array
    {
        if ($stok <= 0) {
            return [
                'label' => 'Habis',
                'class' => 'bg-rose-100 text-rose-700 border-rose-200',
            ];
        }

        if ($stok < self::STOK_MIN) {
            return [
                'label' => 'Menipis',
                'class' => 'bg-amber-100 text-amber-800 border-amber-200',
            ];
        }

        return [
            'label' => 'Aman',
            'class' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        ];
    }
}