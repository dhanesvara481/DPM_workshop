<?php

namespace App\Http\Controllers;

use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatPerubahanStokController extends Controller
{
    public function getRiwayatPerubahanStok(Request $request)
    {
        $q      = $request->input('q');
        $tipe   = $request->input('tipe');
        $dari   = $request->input('dari');
        $sampai = $request->input('sampai');
    
        $sortable = ['tanggal_riwayat_stok', 'kode_barang', 'nama_barang', 'nama_pengguna', 'stok_awal', 'stok_akhir'];
        $sort     = in_array($request->input('sort'), $sortable) ? $request->input('sort') : 'tanggal_riwayat_stok';
        $dir      = $request->input('dir') === 'desc' ? 'desc' : 'asc';
    
        $sortColumn = match(true) {
            in_array($sort, ['kode_barang', 'nama_barang']) => 'barang.' . $sort,
            // ── Sort nama_pengguna pakai snapshot column ──
            $sort === 'nama_pengguna' => 'riwayat_stok.username_snapshot',
            default                   => 'riwayat_stok.' . $sort,
        };
    
        $query = RiwayatStok::with(['barang', 'user', 'barangMasuk', 'barangKeluar'])
            ->leftJoin('barang', 'barang.barang_id', '=', 'riwayat_stok.barang_id')
            ->leftJoin('user',   'user.user_id',     '=', 'riwayat_stok.user_id')
            ->select(
                'riwayat_stok.*',
                DB::raw("COALESCE(riwayat_stok.kode_barang_snapshot, barang.kode_barang, '[Dihapus]') as kode_barang"),
                DB::raw("COALESCE(riwayat_stok.nama_barang_snapshot, barang.nama_barang, '[Barang Dihapus]') as nama_barang"),
                // ── Snapshot-first: tampilkan nama lama walau user sudah ganti username ──
                DB::raw("COALESCE(riwayat_stok.username_snapshot, user.username, '[User Dihapus]') as nama_pengguna"),
                DB::raw("COALESCE(riwayat_stok.email_snapshot, user.email, '') as email_pengguna"),
            );
    
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $like = "%{$q}%";
                $sub->where('barang.kode_barang', 'like', $like)
                    ->orWhere('barang.nama_barang', 'like', $like)
                    // ── Bisa juga search nama pengguna ──
                    ->orWhere('riwayat_stok.username_snapshot', 'like', $like);
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
            ->orderByDesc('riwayat_stok.riwayat_stok_id')
            ->paginate(20)
            ->withQueryString();

        $rows->getCollection()->transform(function ($r) {
            $awal  = (int) $r->stok_awal;
            $akhir = (int) $r->stok_akhir;
            $delta = $akhir - $awal;

            if (!is_null($r->barang_masuk_id) && is_null($r->barang_keluar_id)) {
                $r->tipe = 'masuk';
            } elseif (is_null($r->barang_masuk_id) && !is_null($r->barang_keluar_id)) {
                $r->tipe = 'keluar';
            } else {
                $r->tipe = $delta > 0 ? 'masuk' : ($delta < 0 ? 'keluar' : null);
            }

            $r->qty = abs($delta);
            return $r;
        });

        return view('admin.riwayat_perubahan_stok', compact('rows', 'q', 'tipe', 'dari', 'sampai', 'sort', 'dir'));
    }
}