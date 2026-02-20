<?php

namespace App\Http\Controllers;

use App\Models\RiwayatStok;
use Illuminate\Http\Request;

class RiwayatPerubahanStokController extends Controller
{
    /**
     * GET /riwayat_perubahan_stok
     * Tampilkan semua riwayat perubahan stok dengan filter & search.
     */
    public function getRiwayatPerubahanStok(Request $request)
    {
        $q      = $request->input('q');
        $tipe   = $request->input('tipe');
        $dari   = $request->input('dari');
        $sampai = $request->input('sampai');

        $query = RiwayatStok::with([
                'barang',
                'user',
                'barangMasuk',
                'barangKeluar',
            ])
            ->join('barang', 'barang.barang_id', '=', 'riwayat_stok.barang_id')
            ->join('user',   'user.user_id',     '=', 'riwayat_stok.user_id')
            ->select(
                'riwayat_stok.*',
                'barang.kode_barang',
                'barang.nama_barang',
                'user.username as nama_pengguna',
            );

        // ── Filter keyword ────────────────────────────────────────────────────
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $like = "%{$q}%";
                $sub->where('barang.kode_barang', 'like', $like)
                    ->orWhere('barang.nama_barang', 'like', $like);
            });
        }

        // ── Filter tipe (masuk / keluar) ──────────────────────────────────────
        if ($tipe === 'masuk') {
            $query->whereNotNull('riwayat_stok.barang_masuk_id')
                  ->whereNull('riwayat_stok.barang_keluar_id');
        } elseif ($tipe === 'keluar') {
            $query->whereNull('riwayat_stok.barang_masuk_id')
                  ->whereNotNull('riwayat_stok.barang_keluar_id');
        }

        // ── Filter tanggal ────────────────────────────────────────────────────
        if ($dari) {
            $query->whereDate('riwayat_stok.tanggal_riwayat_stok', '>=', $dari);
        }
        if ($sampai) {
            $query->whereDate('riwayat_stok.tanggal_riwayat_stok', '<=', $sampai);
        }

        $rows = $query->orderByDesc('riwayat_stok.created_at')->paginate(20)->withQueryString();

        return view('admin.riwayat_perubahan_stok', compact('rows', 'q', 'tipe', 'dari', 'sampai'));
    }

}