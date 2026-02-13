<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BarangController extends Controller
{
    /**
     * Tampilan kelola barang (index)
     */
    public function getBarang()
    {
        $barangs = Barang::orderBy('created_at', 'desc')->get();
        
        return view('admin.mengelola_barang.tampilan_barang', [
            'barangs' => $barangs,
        ]);
    }

    /**
     * Tampilan form tambah barang
     */
    public function getTambahBarang()
    {
        return view('admin.mengelola_barang.tambah_barang');
    }

    /**
     * Simpan data barang baru ke database
     */
    public function simpanBarang(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'kode_barang' => 'required|string|max:50|unique:barang,kode_barang',
                'nama_barang' => 'required|string|max:100',
                'satuan' => 'required|in:pcs,unit,gram,set',
                'harga_beli' => 'required|numeric|min:0',
                'harga_jual' => 'required|numeric|min:0',
            ], [
                'kode_barang.required' => 'Kode barang wajib diisi',
                'kode_barang.unique' => 'Kode barang sudah digunakan',
                'nama_barang.required' => 'Nama barang wajib diisi',
                'satuan.required' => 'Satuan wajib dipilih',
                'satuan.in' => 'Satuan tidak valid',
                'harga_beli.required' => 'Harga beli wajib diisi',
                'harga_beli.numeric' => 'Harga beli harus berupa angka',
                'harga_jual.required' => 'Harga jual wajib diisi',
                'harga_jual.numeric' => 'Harga jual harus berupa angka',
            ]);

            // Parse harga (menghilangkan format Rp dan titik)
            $validated['harga_beli'] = (float) str_replace(['.', ','], ['', '.'], preg_replace('/[^0-9,.]/', '', $request->harga_beli));
            $validated['harga_jual'] = (float) str_replace(['.', ','], ['', '.'], preg_replace('/[^0-9,.]/', '', $request->harga_jual));
            
            // Set stok awal = 0 (stok akan diupdate lewat stok masuk)
            $validated['stok'] = '0';

            // Simpan ke database
            $barang = Barang::create($validated);

            return redirect()
                ->route('mengelola_barang')
                ->with('success', 'Barang berhasil ditambahkan');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating barang: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Tampilan form edit barang
     */
    public function getUbahBarang($id)
    {
        try {
            $barang = Barang::findOrFail($id);

            return view('admin.mengelola_barang.ubah_barang', [
                'barang' => $barang,
            ]);
        } catch (\Exception $e) {
            return redirect()
                ->route('mengelola_barang')
                ->with('error', 'Barang tidak ditemukan');
        }
    }

    /**
     * Update data barang
     */
    public function perbaruiBarang(Request $request, $id)
    {
        try {
            $barang = Barang::findOrFail($id);

            $validated = $request->validate([
                'kode_barang' => 'required|string|max:50|unique:barang,kode_barang,' . $id . ',barang_id', // ← tambah ,barang_id
                'nama_barang' => 'required|string|max:100',
                'satuan' => 'required|in:pcs,unit,gram,set',
                'harga_beli' => 'required|numeric|min:0',
                'harga_jual' => 'required|numeric|min:0',
            ], [
                'kode_barang.required' => 'Kode barang wajib diisi',
                'kode_barang.unique' => 'Kode barang sudah digunakan',
                'nama_barang.required' => 'Nama barang wajib diisi',
                'satuan.required' => 'Satuan wajib dipilih',
                'satuan.in' => 'Satuan tidak valid',
                'harga_beli.required' => 'Harga beli wajib diisi',
                'harga_beli.numeric' => 'Harga beli harus berupa angka',
                'harga_jual.required' => 'Harga jual wajib diisi',
                'harga_jual.numeric' => 'Harga jual harus berupa angka',
            ]);

            $validated['harga_beli'] = (float) str_replace(['.', ','], ['', '.'], preg_replace('/[^0-9,.]/', '', $request->harga_beli));
            $validated['harga_jual'] = (float) str_replace(['.', ','], ['', '.'], preg_replace('/[^0-9,.]/', '', $request->harga_jual));

            $barang->update($validated);

            return redirect()
                ->route('mengelola_barang')
                ->with('success', 'Barang berhasil diperbarui');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating barang: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data')
                ->withInput();
        }
    }


    /**
     * Hapus data barang
     */
    public function hapusBarang($id)
    {
        try {
            $barang = Barang::findOrFail($id);
            
            // if ($barang->detailInvoice()->exists()) {
            //     return redirect()
            //         ->route('mengelola_barang')
            //         ->with('error', 'Barang tidak dapat dihapus karena sudah digunakan dalam transaksi');
            // }

            $barang->delete();

            return redirect()
                ->route('mengelola_barang') // ← pastikan ini
                ->with('success', 'Barang berhasil dihapus');

        } catch (\Exception $e) {
            Log::error('Error deleting barang: ' . $e->getMessage());
            return redirect()
                ->route('mengelola_barang') // ← pastikan ini
                ->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }
    public function buatKodeBarang()
    {
        $kode = DB::transaction(function () {
    
            // Ambil kode terakhir dengan LOCK
            $last = Barang::lockForUpdate()
                ->orderBy('barang_id', 'desc')
                ->first();
    
            $number = 1;
    
            if ($last) {
                // Ambil angka dari BRG-000123
                preg_match('/(\d+)$/', $last->kode_barang, $match);
                $number = isset($match[1]) ? ((int)$match[1] + 1) : $last->barang_id + 1;
            }
    
            // Loop jika tabrakan (safety)
            do {
                $kode = 'BRG-' . str_pad($number, 5, '0', STR_PAD_LEFT);
                $number++;
            } while (Barang::where('kode_barang', $kode)->exists());
    
            return $kode;
        });
    
        return response()->json(['kode' => $kode]);
    }



}