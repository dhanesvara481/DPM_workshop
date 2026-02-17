<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BarangMasuk;
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
        // Ambil semua barang untuk dropdown
        $barangs = Barang::orderBy('kode_barang', 'asc')->get();
        
        // Ambil riwayat barang masuk dengan join ke tabel barang
        $barangMasuk = BarangMasuk::join('barang', 'barang_masuk.barang_id', '=', 'barang.barang_id')
            ->select(
                'barang_masuk.*',
                'barang.kode_barang',
                'barang.nama_barang',
                'barang.satuan'
            )
            ->orderBy('barang_masuk.tanggal_masuk', 'desc')
            ->orderBy('barang_masuk.created_at', 'desc')
            ->limit(50)
            ->get();

        return view('admin.barang_masuk', [
            'barangs' => $barangs,
            'barangMasuk' => $barangMasuk,
        ]);
    }

    /**
     * Store barang masuk
     */
    public function simpanBarangMasuk(Request $request)
    {
        try {
            // Validasi
            $validated = $request->validate([
                'barang_id' => 'required|exists:barang,barang_id',
                'qty_masuk' => 'required|integer|min:1',
                'tanggal' => 'required|date',
            ], [
                'barang_id.required' => 'Pilih barang terlebih dahulu',
                'barang_id.exists' => 'Barang tidak ditemukan',
                'qty_masuk.required' => 'Jumlah stok masuk wajib diisi',
                'qty_masuk.integer' => 'Jumlah harus berupa angka',
                'qty_masuk.min' => 'Jumlah minimal 1',
                'tanggal.required' => 'Tanggal wajib diisi',
                'tanggal.date' => 'Format tanggal tidak valid',
            ]);

            DB::beginTransaction();

            // Ambil barang
            $barang = Barang::findOrFail($validated['barang_id']);

            // Simpan barang masuk
            $barangMasuk = BarangMasuk::create([
                'barang_id' => $validated['barang_id'],
                'user_id' => Auth::id() ?? 1,
                'jumlah_masuk' => $validated['qty_masuk'],
                'tanggal_masuk' => $validated['tanggal'],
            ]);

            // Update stok barang
            $stokLama = (int) $barang->stok;
            $stokBaru = $stokLama + (int) $validated['qty_masuk'];
            $barang->update(['stok' => (string) $stokBaru]);

            DB::commit();

            return redirect()
                ->route('barang_masuk')
                ->with('success', "Berhasil menambah stok {$barang->nama_barang} sebanyak {$validated['qty_masuk']} {$barang->satuan}. Stok sekarang: {$stokBaru}");

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing barang masuk: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }
}