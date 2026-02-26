<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\RiwayatStok;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class BarangMasukController extends Controller
{
    public function getBarangMasuk()
    {
        $barangs = Barang::orderBy('kode_barang', 'asc')->get();

        $barangMasuk = BarangMasuk::join('barang', 'barang_masuk.barang_id', '=', 'barang.barang_id')
            ->select(
                'barang_masuk.barang_masuk_id',
                'barang_masuk.barang_id',
                'barang_masuk.user_id',
                'barang_masuk.jumlah_masuk',
                'barang_masuk.tanggal_masuk',
                'barang_masuk.created_at',
                'barang.kode_barang',
                'barang.nama_barang',
                'barang.satuan',
                'barang.stok'
            )
            ->orderBy('barang_masuk.tanggal_masuk', 'desc')
            ->orderBy('barang_masuk.created_at', 'desc')
            ->limit(50)
            ->get();

        return view('admin.barang_masuk', [
            'barangs'     => $barangs,
            'barangMasuk' => $barangMasuk,
        ]);
    }

    /**
     * Simpan barang masuk dan update stok.
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
     *                 tidak ada riwayat sama sekali -> stok_awal = qty itu sendiri (pertama kali)
     *
     * - stok_akhir = stok_akhir record TERAKHIR hari ini + qty
     */
    public function simpanBarangMasuk(Request $request)
    {
        try {
            $validated = $request->validate([
                'barang_id' => 'required|exists:barang,barang_id',
                'qty_masuk' => 'required|integer|min:1',
                'tanggal'   => 'required|date',
            ], [
                'barang_id.required' => 'Pilih barang terlebih dahulu.',
                'barang_id.exists'   => 'Barang tidak ditemukan.',
                'qty_masuk.required' => 'Jumlah stok masuk wajib diisi.',
                'qty_masuk.integer'  => 'Jumlah harus berupa angka.',
                'qty_masuk.min'      => 'Jumlah minimal 1.',
                'tanggal.required'   => 'Tanggal wajib diisi.',
                'tanggal.date'       => 'Format tanggal tidak valid.',
            ]);

            DB::beginTransaction();

            $barang = Barang::where('barang_id', $validated['barang_id'])->firstOrFail();

            $userId = Auth::id();
            if (!$userId) {
                throw new \Exception('User tidak terautentikasi. Silakan login terlebih dahulu.');
            }

            $tanggalInput = $validated['tanggal'];
            $qty          = (int) $validated['qty_masuk'];

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
                $stokAkhir = (int) $riwayatHariIniTerakhir->stok_akhir + $qty;

            } elseif ($riwayatSebelumnya) {
                // Hari baru, ada riwayat kemarin
                $stokAwal  = (int) $riwayatSebelumnya->stok_akhir;
                $stokAkhir = $stokAwal + $qty;

            } else {
                // Pertama kali input, belum ada riwayat sama sekali
                $stokAwal  = $qty;
                $stokAkhir = $qty;
            }

            $barangMasuk = BarangMasuk::create([
                'barang_id'     => $validated['barang_id'],
                'user_id'       => $userId,
                'jumlah_masuk'  => $qty,
                'tanggal_masuk' => $tanggalInput,
            ]);

            $barang->update(['stok' => (string) $stokAkhir]);

            RiwayatStok::create([
                'barang_id'            => $validated['barang_id'],
                'user_id'              => $userId,
                'barang_masuk_id'      => $barangMasuk->barang_masuk_id,
                'barang_keluar_id'     => null,
                'tanggal_riwayat_stok' => $tanggalInput,
                'stok_awal'            => $stokAwal,
                'stok_akhir'           => $stokAkhir,
            ]);

            DB::commit();

            return redirect()
                ->route('barang_masuk')
                ->with('success', "Berhasil menambah stok {$barang->nama_barang} sebanyak {$qty} {$barang->satuan}. Stok sekarang: {$stokAkhir}.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Barang tidak ditemukan.')->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing barang masuk: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}