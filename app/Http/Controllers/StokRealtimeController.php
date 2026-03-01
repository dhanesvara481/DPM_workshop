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
        $q = $request->input('q');

        $query = Barang::query()->orderBy('barang_id', 'asc');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $like = "%{$q}%";
                $sub->where('kode_barang', 'like', $like)
                    ->orWhere('nama_barang', 'like', $like);
            });
        }

        $barangs = $query->get();

        return view('admin.stok_realtime', compact('barangs', 'q'));
    }

    public function print(Request $request)
    {
        $barangs = Barang::orderBy('barang_id', 'asc')->get();

        return view('admin.print.stokrealtime', compact('barangs'));
    }

    // ── Staff ────────────────────────────────────────────────────────────────

    public function getStokRealtimeStaff(Request $request)
    {
        $q = $request->input('q');

        $query = Barang::query()->orderBy('barang_id', 'asc');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $like = "%{$q}%";
                $sub->where('kode_barang', 'like', $like)
                    ->orWhere('nama_barang', 'like', $like);
            });
        }

        $barangs = $query->get();

        return view('staff.stok_realtime.stok_realtime_staff', compact('barangs', 'q'));
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
    public static function resolveStokStatus(int $stok): array
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