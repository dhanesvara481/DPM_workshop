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

        $barangKeluar = BarangKeluar::with('barang')
            ->select(
                'barang_keluar.*',
                'barang.kode_barang',
                'barang.nama_barang',
                DB::raw("DATE_FORMAT(barang_keluar.tanggal_keluar, '%d-%m-%Y') as tanggal"),
                'barang_keluar.jumlah_keluar as qty_keluar',
            )
            ->join('barang', 'barang.barang_id', '=', 'barang_keluar.barang_id')
            ->latest('barang_keluar.created_at')
            ->get();

        return view('admin.barang_keluar', compact('barangs', 'barangKeluar'));
    }

    /**
     * Simpan barang keluar dan kurangi stok.
     *
     * Aturan stok_awal & stok_akhir:
     *
     *   Hari 1  masuk  qty 100  => stok_awal 100, stok_akhir 100
     *   Hari 1  masuk  qty  50  => stok_awal 100, stok_akhir 150
     *   Hari 1  keluar qty  25  => stok_awal 100, stok_akhir 125
     *   Hari 2  masuk  qty  55  => stok_awal 125, stok_akhir 180
     *
     * - stok_awal  = stok_awal record PERTAMA hari ini (tetap sepanjang hari)
     *               Jika belum ada transaksi hari ini:
     *                 ada riwayat sebelumnya  -> stok_awal = stok_akhir terakhir kemarin
     *                 tidak ada riwayat sama sekali -> stok_awal = stok barang saat ini
     *
     * - stok_akhir = stok_akhir record TERAKHIR hari ini - qty
     */
    public function simpanBarangKeluar(Request $request)
    {
        $validated = $request->validate([
            'barang_id'  => ['required', 'exists:barang,barang_id'],
            'qty_keluar' => ['required', 'integer', 'min:1'],
            'tanggal'    => ['required', 'date'],
            'keterangan' => ['required', 'in:Barang Rusak,Barang Dikembalikan,Penyesuaian Stok'],
        ], [
            'barang_id.required'  => 'Pilih kode barang terlebih dahulu.',
            'barang_id.exists'    => 'Barang tidak ditemukan.',
            'qty_keluar.required' => 'Jumlah keluar wajib diisi.',
            'qty_keluar.min'      => 'Jumlah keluar minimal 1.',
            'tanggal.required'    => 'Tanggal wajib diisi.',
            'keterangan.required' => 'Pilih keterangan.',
            'keterangan.in'       => 'Keterangan tidak valid.',
        ]);

        $barang = Barang::where('barang_id', $validated['barang_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

        if ((int) $barang->stok < (int) $validated['qty_keluar']) {
            return back()
                ->withInput()
                ->withErrors(['qty_keluar' => 'Jumlah keluar ('.$validated['qty_keluar'].') melebihi stok tersedia ('.$barang->stok.').']);
        }

        DB::transaction(function () use ($validated, $barang) {

            $tanggalInput = $validated['tanggal'];
            $qty          = (int) $validated['qty_keluar'];

            // Record pertama hari ini -> untuk stok_awal awal hari (tidak berubah)
            $riwayatHariIniPertama = RiwayatStok::where('barang_id', $validated['barang_id'])
                ->whereDate('tanggal_riwayat_stok', $tanggalInput)
                ->orderBy('riwayat_stok_id', 'asc')
                ->first();

            // Record terakhir hari ini -> untuk melanjutkan stok_akhir
            $riwayatHariIniTerakhir = RiwayatStok::where('barang_id', $validated['barang_id'])
                ->whereDate('tanggal_riwayat_stok', $tanggalInput)
                ->orderBy('riwayat_stok_id', 'desc')
                ->first();

            // Record terakhir sebelum hari ini -> menjadi stok_awal hari baru
            $riwayatSebelumnya = RiwayatStok::where('barang_id', $validated['barang_id'])
                ->whereDate('tanggal_riwayat_stok', '<', $tanggalInput)
                ->orderBy('tanggal_riwayat_stok', 'desc')
                ->orderBy('riwayat_stok_id', 'desc')
                ->first();

            if ($riwayatHariIniPertama) {
                // Sudah ada transaksi hari ini
                $stokAwal  = (int) $riwayatHariIniPertama->stok_awal;
                $stokAkhir = (int) $riwayatHariIniTerakhir->stok_akhir - $qty;

            } elseif ($riwayatSebelumnya) {
                // Hari baru, ada riwayat kemarin
                $stokAwal  = (int) $riwayatSebelumnya->stok_akhir;
                $stokAkhir = $stokAwal - $qty;

            } else {
                // Belum ada riwayat sama sekali (langsung keluar pertama kali)
                $stokAwal  = (int) $barang->stok;
                $stokAkhir = $stokAwal - $qty;
            }

            $barang->decrement('stok', $qty);

            $barangKeluar = BarangKeluar::create([
                'user_id'        => Auth::id(),
                'barang_id'      => $validated['barang_id'],
                'jumlah_keluar'  => $qty,
                'tanggal_keluar' => $tanggalInput,
                'keterangan'     => $validated['keterangan'],
                'ref_invoice'    => null,
            ]);

            RiwayatStok::create([
                'barang_id'            => $validated['barang_id'],
                'user_id'              => Auth::id(),
                'barang_masuk_id'      => null,
                'barang_keluar_id'     => $barangKeluar->barang_keluar_id,
                'tanggal_riwayat_stok' => $tanggalInput,
                'stok_awal'            => $stokAwal,
                'stok_akhir'           => $stokAkhir,
            ]);
        });

        return redirect()
            ->route('barang_keluar')
            ->with('success', 'Stok keluar berhasil dicatat.');
    }

    public function create()  {}
    public function show(string $id)  {}
    public function edit(string $id)  {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}