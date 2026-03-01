<?php

namespace App\Http\Controllers;

use App\Models\RiwayatStok;
use Illuminate\Http\Request;

class RiwayatPerubahanStokController extends Controller
{
    public function getRiwayatPerubahanStok(Request $request)
    {
        $q      = $request->input('q');
        $tipe   = $request->input('tipe');
        $dari   = $request->input('dari');
        $sampai = $request->input('sampai');
    
        // ── Sort ──────────────────────────────────────────────────────────────
        $sortable = [ 'tanggal_riwayat_stok', 'kode_barang', 'nama_barang', 'nama_pengguna', 'stok_awal', 'stok_akhir'];
        $sort     = in_array($request->input('sort'), $sortable) ? $request->input('sort') : 'tanggal_riwayat_stok';
        $dir      = $request->input('dir') === 'desc' ? 'desc' : 'asc';
    
        // prefix tabel agar tidak ambigu
        $sortColumn = match(true) {
            in_array($sort, ['kode_barang', 'nama_barang']) => 'barang.' . $sort,
            $sort === 'nama_pengguna'                       => 'user.username',
            default                                         => 'riwayat_stok.' . $sort,
        };
    
        $query = RiwayatStok::with(['barang', 'user', 'barangMasuk', 'barangKeluar'])
            ->join('barang', 'barang.barang_id', '=', 'riwayat_stok.barang_id')
            ->join('user',   'user.user_id',     '=', 'riwayat_stok.user_id')
            ->select(
                'riwayat_stok.*',
                'barang.kode_barang',
                'barang.nama_barang',
                'user.username as nama_pengguna',
            );
    
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $like = "%{$q}%";
                $sub->where('barang.kode_barang', 'like', $like)
                    ->orWhere('barang.nama_barang', 'like', $like);
            });
        }
    
        if ($tipe === 'masuk') {
            $query->whereNotNull('riwayat_stok.barang_masuk_id')
                  ->whereNull('riwayat_stok.barang_keluar_id');
        } elseif ($tipe === 'keluar') {
            $query->whereNull('riwayat_stok.barang_masuk_id')
                  ->whereNotNull('riwayat_stok.barang_keluar_id');
        }
    
        if ($dari) {
            $query->whereDate('riwayat_stok.tanggal_riwayat_stok', '>=', $dari);
        }
        if ($sampai) {
            $query->whereDate('riwayat_stok.tanggal_riwayat_stok', '<=', $sampai);
        }
    
        $rows = $query
            ->orderBy($sortColumn, $dir)
            ->orderByDesc('riwayat_stok.riwayat_stok_id') // tiebreaker
            ->paginate(20)
            ->withQueryString();

        // ✅ Tambahin ini:
        $rows->getCollection()->transform(function ($r) {
            $awal  = (int) $r->stok_awal;
            $akhir = (int) $r->stok_akhir;
            $delta = $akhir - $awal;

            // Tipe utama dari kolom relasi (paling valid)
            if (!is_null($r->barang_masuk_id) && is_null($r->barang_keluar_id)) {
                $r->tipe = 'masuk';
            } elseif (is_null($r->barang_masuk_id) && !is_null($r->barang_keluar_id)) {
                $r->tipe = 'keluar';
            } else {
                // fallback kalau ada data "aneh" (dua-duanya terisi / dua-duanya null)
                $r->tipe = $delta > 0 ? 'masuk' : ($delta < 0 ? 'keluar' : null);
            }

            $r->qty = abs($delta); // qty perubahan stok (selisih)
            return $r;
        });

    
        return view('admin.riwayat_perubahan_stok', compact('rows', 'q', 'tipe', 'dari', 'sampai', 'sort', 'dir'));
    }
}