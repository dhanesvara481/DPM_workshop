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
    /**
     * Tampilan barang masuk
     */
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
     * Simpan barang masuk & update stok
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
                // Transaksi pertama kali → stok_awal = qty itu sendiri
                $stokAwal            = (int) $validated['qty_masuk'];
                $stokAkhirSebelumnya = 0;
            }

            $stokBaru = $stokAkhirSebelumnya + (int) $validated['qty_masuk'];

            // Simpan ke barang_masuk
            $barangMasuk = BarangMasuk::create([
                'barang_id'     => $validated['barang_id'],
                'user_id'       => $userId,
                'jumlah_masuk'  => $validated['qty_masuk'],
                'tanggal_masuk' => $tanggalInput,
            ]);

            // Update stok barang
            $barang->update(['stok' => (string) $stokBaru]);

            // Catat ke riwayat_stok
            RiwayatStok::create([
                'barang_id'            => $validated['barang_id'],
                'user_id'              => $userId,
                'barang_masuk_id'      => $barangMasuk->barang_masuk_id,
                'barang_keluar_id'     => null,
                'tanggal_riwayat_stok' => $tanggalInput,
                'stok_awal'            => $stokAwal,
                'stok_akhir'           => $stokBaru,
            ]);

            DB::commit();

            return redirect()
                ->route('barang_masuk')
                ->with('success', "Berhasil menambah stok {$barang->nama_barang} sebanyak {$validated['qty_masuk']} {$barang->satuan}. Stok sekarang: {$stokBaru}.");

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