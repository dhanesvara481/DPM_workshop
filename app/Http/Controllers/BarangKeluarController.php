<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    public function getBarangKeluar(Request $request)
    {
        $barangs = Barang::orderBy('kode_barang')->get();

        $sortable = ['tanggal_keluar', 'kode_barang', 'nama_barang', 'jumlah_keluar', 'keterangan'];
        $sort     = in_array($request->sort, $sortable) ? $request->sort : 'tanggal_keluar';
        $dir      = $request->dir === 'asc' ? 'asc' : 'desc';

        $barangKeluar = BarangKeluar::leftJoin('barang', 'barang.barang_id', '=', 'barang_keluar.barang_id')
            ->select(
                'barang_keluar.*',
                DB::raw("COALESCE(barang_keluar.kode_barang_snapshot, barang.kode_barang, '[Dihapus]') as kode_barang"),
                DB::raw("COALESCE(barang_keluar.nama_barang_snapshot, barang.nama_barang, '[Barang Dihapus]') as nama_barang"),
                'barang_keluar.jumlah_keluar as qty_keluar',
                // ── Nama pengguna: snapshot dulu ──
                DB::raw("COALESCE(barang_keluar.username_snapshot, '[User Dihapus]') as nama_pengguna"),
            )
            ->when($request->search, function ($q) use ($request) {
                $s = $request->search;
                $q->where('barang.kode_barang', 'like', "%$s%")
                  ->orWhere('barang.nama_barang', 'like', "%$s%")
                  ->orWhere('barang_keluar.keterangan', 'like', "%$s%");
            })
            ->orderBy($sort === 'kode_barang' || $sort === 'nama_barang' ? 'barang.'.$sort : 'barang_keluar.'.$sort, $dir)
            ->paginate(10)
            ->withQueryString();

        return view('admin.barang_keluar', compact('barangs', 'barangKeluar', 'sort', 'dir'));
    }

    public function simpanBarangKeluar(Request $request)
    {
        $validated = $request->validate([
            'barang_id'  => ['required', 'exists:barang,barang_id'],
            'qty_keluar' => ['required', 'integer', 'min:1'],
            'tanggal'    => ['required', 'date'],
            'keterangan' => ['required', 'in:Barang Rusak,Barang Dikembalikan,Penyesuaian Stok'],
            'foto_bukti' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
        ], [
            'barang_id.required'  => 'Pilih kode barang terlebih dahulu.',
            'barang_id.exists'    => 'Barang tidak ditemukan.',
            'qty_keluar.required' => 'Jumlah keluar wajib diisi.',
            'qty_keluar.min'      => 'Jumlah keluar minimal 1.',
            'tanggal.required'    => 'Tanggal wajib diisi.',
            'keterangan.required' => 'Pilih keterangan.',
            'keterangan.in'       => 'Keterangan tidak valid.',
            'foto_bukti.image'    => 'File harus berupa gambar.',
            'foto_bukti.mimes'    => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'foto_bukti.max'      => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $barang = Barang::where('barang_id', $validated['barang_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

        if ((int) $barang->stok < (int) $validated['qty_keluar']) {
            return back()
                ->withInput()
                ->withErrors(['qty_keluar' => 'Jumlah keluar ('.$validated['qty_keluar'].') melebihi stok tersedia ('.$barang->stok.').']);
        }

        // ── Upload foto ────────────────────────────────────────────────────────
        $fotoBuktiPath = null;
        if ($request->hasFile('foto_bukti')) {
            $fotoBuktiPath = $request->file('foto_bukti')
                ->store('barang_keluar/' . now()->format('Y/m'), 'public');
        }

        // ── Ambil data user saat ini untuk snapshot ───────────────────────────
        $currentUser = Auth::user();

        DB::transaction(function () use ($validated, $barang, $fotoBuktiPath, $currentUser) {
            $tanggalInput = $validated['tanggal'];
            $waktuKeluar  = now();
            $qty          = (int) $validated['qty_keluar'];

            $riwayatHariIniPertama = RiwayatStok::where('barang_id', $validated['barang_id'])
                ->whereDate('tanggal_riwayat_stok', $tanggalInput)
                ->orderBy('riwayat_stok_id', 'asc')
                ->first();

            $riwayatHariIniTerakhir = RiwayatStok::where('barang_id', $validated['barang_id'])
                ->whereDate('tanggal_riwayat_stok', $tanggalInput)
                ->orderBy('riwayat_stok_id', 'desc')
                ->first();

            $riwayatSebelumnya = RiwayatStok::where('barang_id', $validated['barang_id'])
                ->whereDate('tanggal_riwayat_stok', '<', $tanggalInput)
                ->orderBy('tanggal_riwayat_stok', 'desc')
                ->orderBy('riwayat_stok_id', 'desc')
                ->first();

            if ($riwayatHariIniPertama) {
                $stokAwal  = (int) $riwayatHariIniPertama->stok_awal;
                $stokAkhir = (int) $riwayatHariIniTerakhir->stok_akhir - $qty;
            } elseif ($riwayatSebelumnya) {
                $stokAwal  = (int) $riwayatSebelumnya->stok_akhir;
                $stokAkhir = $stokAwal - $qty;
            } else {
                $stokAwal  = (int) $barang->stok;
                $stokAkhir = $stokAwal - $qty;
            }

            $barang->decrement('stok', $qty);

            $barangKeluar = BarangKeluar::create([
                'user_id'              => $currentUser->user_id,
                'barang_id'            => $validated['barang_id'],
                'jumlah_keluar'        => $qty,
                'tanggal_keluar'       => $waktuKeluar,
                'keterangan'           => $validated['keterangan'],
                'ref_invoice'          => null,
                'kode_barang_snapshot' => $barang->kode_barang,
                'nama_barang_snapshot' => $barang->nama_barang,
                'satuan_snapshot'      => $barang->satuan,
                'foto_bukti'           => $fotoBuktiPath,
                // ── Snapshot user ─────────────────────────────────────────────
                'username_snapshot'    => $currentUser->username,
                'email_snapshot'       => $currentUser->email,
            ]);

            RiwayatStok::create([
                'barang_id'            => $validated['barang_id'],
                'user_id'              => $currentUser->user_id,
                'barang_masuk_id'      => null,
                'barang_keluar_id'     => $barangKeluar->barang_keluar_id,
                'tanggal_riwayat_stok' => $waktuKeluar,
                'stok_awal'            => $stokAwal,
                'stok_akhir'           => $stokAkhir,
                'kode_barang_snapshot' => $barang->kode_barang,
                'nama_barang_snapshot' => $barang->nama_barang,
                // ── Snapshot user ─────────────────────────────────────────────
                'username_snapshot'    => $currentUser->username,
                'email_snapshot'       => $currentUser->email,
            ]);
        });

        return redirect()
            ->route('barang_keluar')
            ->with('success', 'Stok keluar berhasil dicatat.');
    }
}