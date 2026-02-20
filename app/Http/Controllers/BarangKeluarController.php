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
    /**
     * GET /barang_keluar
     * Tampilkan form input + riwayat barang keluar.
     */
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
     * POST /barang_keluar/store
     * Validasi, kurangi stok, simpan transaksi keluar.
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

            // Cek apakah sudah ada riwayat di hari yang SAMA
            $riwayatHariIni = RiwayatStok::where('barang_id', $validated['barang_id'])
                ->whereDate('tanggal_riwayat_stok', $tanggalInput)
                ->orderBy('created_at', 'desc')
                ->first();

            // Ambil stok_akhir hari sebelumnya
            $riwayatSebelumnya = RiwayatStok::where('barang_id', $validated['barang_id'])
                ->whereDate('tanggal_riwayat_stok', '<', $tanggalInput)
                ->orderBy('tanggal_riwayat_stok', 'desc')
                ->orderBy('created_at', 'desc')
                ->first();

            if ($riwayatHariIni) {
                // Sudah ada transaksi hari ini → stok_awal tetap dari awal hari ini
                $stokAwal            = (int) $riwayatHariIni->stok_awal;
                $stokAkhirSebelumnya = (int) $riwayatHariIni->stok_akhir;
            } elseif ($riwayatSebelumnya) {
                // Hari baru → stok_awal = stok_akhir hari sebelumnya
                $stokAwal            = (int) $riwayatSebelumnya->stok_akhir;
                $stokAkhirSebelumnya = $stokAwal;
            } else {
                // Belum ada riwayat sama sekali → stok_awal = stok barang saat ini
                $stokAwal            = (int) $barang->stok;
                $stokAkhirSebelumnya = $stokAwal;
            }

            $stokBaru = $stokAkhirSebelumnya - (int) $validated['qty_keluar'];

            // Kurangi stok barang
            $barang->decrement('stok', (int) $validated['qty_keluar']);

            // Simpan barang_keluar
            $barangKeluar = BarangKeluar::create([
                'user_id'        => Auth::id(),
                'barang_id'      => $validated['barang_id'],
                'jumlah_keluar'  => $validated['qty_keluar'],
                'tanggal_keluar' => $tanggalInput,
                'keterangan'     => $validated['keterangan'],
                'ref_invoice'    => null,
            ]);

            // Catat ke riwayat_stok
            RiwayatStok::create([
                'barang_id'            => $validated['barang_id'],
                'user_id'              => Auth::id(),
                'barang_masuk_id'      => null,
                'barang_keluar_id'     => $barangKeluar->barang_keluar_id,
                'tanggal_riwayat_stok' => $tanggalInput,
                'stok_awal'            => $stokAwal,
                'stok_akhir'           => $stokBaru,
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