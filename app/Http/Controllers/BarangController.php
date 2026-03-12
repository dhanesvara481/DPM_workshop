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
        $sortable = ['kode_barang', 'nama_barang', 'satuan', 'stok', 'harga_beli', 'harga_jual'];
        $sort     = in_array(request('sort'), $sortable) ? request('sort') : 'created_at';
        $dir      = request('dir') === 'desc' ? 'desc' : 'asc';

        $barangs = Barang::when(request('search'), function($q) {
                        $s = request('search');
                        $q->where('kode_barang', 'like', "%$s%")
                          ->orWhere('nama_barang', 'like', "%$s%");
                    })
                    ->orderBy($sort, $dir)
                    ->paginate(10)
                    ->withQueryString();

        return view('admin.mengelola_barang.tampilan_barang', [
            'barangs' => $barangs,
            'stokMin' => 25,
            'sort'    => $sort,
            'dir'     => $dir,
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
            $validated = $request->validate([
                'kode_barang' => 'required|string|max:50|unique:barang,kode_barang',
                'nama_barang' => 'required|string|max:100',
                'satuan'      => 'required|in:pcs,unit,set',
                'harga_beli'  => 'required|min:0',
                'harga_jual'  => 'required|min:0',
            ], [
                'kode_barang.required' => 'Kode barang wajib diisi',
                'kode_barang.unique'   => 'Kode barang sudah digunakan',
                'nama_barang.required' => 'Nama barang wajib diisi',
                'satuan.required'      => 'Satuan wajib dipilih',
                'satuan.in'            => 'Satuan tidak valid',
                'harga_beli.required'  => 'Harga beli wajib diisi',
                'harga_jual.required'  => 'Harga jual wajib diisi',
            ]);

            // Parse harga dari format Rp 1.000.000 → string desimal yang aman
            // Hindari (float) cast agar tidak ada floating point error pada nilai uang
            $validated['harga_beli'] = $this->parseHarga($request->harga_beli);
            $validated['harga_jual'] = $this->parseHarga($request->harga_jual);

            // Stok awal = 0
            $validated['stok'] = 0;

            Barang::create($validated);

            return redirect()
                ->route('mengelola_barang')
                ->with('success', 'Barang berhasil ditambahkan');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating barang: ' . $e->getMessage());
            return redirect()->back()
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
            return redirect()->route('mengelola_barang')->with('error', 'Barang tidak ditemukan');
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
                'kode_barang' => 'required|string|max:50|unique:barang,kode_barang,' . $id . ',barang_id',
                'nama_barang' => 'required|string|max:100',
                'satuan'      => 'required|in:pcs,unit,set',
                'harga_beli'  => 'required|min:0',
                'harga_jual'  => 'required|min:0',
            ], [
                'kode_barang.required' => 'Kode barang wajib diisi',
                'kode_barang.unique'   => 'Kode barang sudah digunakan',
                'nama_barang.required' => 'Nama barang wajib diisi',
                'satuan.required'      => 'Satuan wajib dipilih',
                'satuan.in'            => 'Satuan tidak valid',
                'harga_beli.required'  => 'Harga beli wajib diisi',
                'harga_jual.required'  => 'Harga jual wajib diisi',
            ]);

            // Parse harga dari format Rp 1.000.000 → string desimal yang aman
            $validated['harga_beli'] = $this->parseHarga($request->harga_beli);
            $validated['harga_jual'] = $this->parseHarga($request->harga_jual);

            $barang->update($validated);

            return redirect()
                ->route('mengelola_barang')
                ->with('success', 'Barang berhasil diperbarui');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating barang: ' . $e->getMessage());
            return redirect()->back()
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
            $barang->delete();

            return redirect()->route('mengelola_barang')->with('success', 'Barang berhasil dihapus');

        } catch (\Exception $e) {
            Log::error('Error deleting barang: ' . $e->getMessage());
            return redirect()->route('mengelola_barang')->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }

    /**
     * Generate kode barang otomatis
     */
    public function buatKodeBarang()
    {
        $kode = DB::transaction(function () {
            $last = Barang::lockForUpdate()
                ->orderBy('barang_id', 'desc')
                ->first();

            $number = 1;

            if ($last) {
                preg_match('/(\d+)$/', $last->kode_barang, $match);
                $number = isset($match[1]) ? ((int) $match[1] + 1) : $last->barang_id + 1;
            }

            do {
                $kode = 'BRG-' . str_pad($number, 5, '0', STR_PAD_LEFT);
                $number++;
            } while (Barang::where('kode_barang', $kode)->exists());

            return $kode;
        });

        return response()->json(['kode' => $kode]);
    }

    // ── Private Helpers ──────────────────────────────────────────────────────

    /**
     * Parse input harga dari format "Rp 1.000.000" atau "1000000,50"
     * menjadi string desimal yang aman untuk disimpan ke kolom DECIMAL di DB.
     *
     * Tidak menggunakan (float) cast untuk menghindari floating point error.
     *
     * @param  string|null  $raw  Input dari form
     * @return string             Contoh: "1000000.00"
     */
    private function parseHarga(?string $raw): string
    {
        if (is_null($raw) || $raw === '') {
            return '0.00';
        }

        // Hapus semua karakter selain digit, koma, dan titik
        $cleaned = preg_replace('/[^0-9.,]/', '', $raw);

        // Jika format menggunakan koma sebagai desimal (e.g. "1.000.000,50")
        // → ganti titik (pemisah ribuan) lalu ganti koma jadi titik desimal
        if (str_contains($cleaned, ',')) {
            $cleaned = str_replace('.', '', $cleaned);
            $cleaned = str_replace(',', '.', $cleaned);
        } else {
            // Format pakai titik sebagai pemisah ribuan (e.g. "1.000.000")
            // Cek apakah titik terakhir adalah desimal (e.g. "1000.50")
            $lastDot = strrpos($cleaned, '.');
            if ($lastDot !== false && strlen($cleaned) - $lastDot - 1 === 2) {
                // Ada 2 digit setelah titik terakhir → kemungkinan desimal
                // Hapus titik ribuan yang lain
                $integer = str_replace('.', '', substr($cleaned, 0, $lastDot));
                $decimal = substr($cleaned, $lastDot + 1);
                $cleaned = $integer . '.' . $decimal;
            } else {
                // Semua titik adalah pemisah ribuan
                $cleaned = str_replace('.', '', $cleaned);
            }
        }

        // Pastikan format valid, fallback ke '0.00' jika tidak valid
        if (!is_numeric($cleaned)) {
            return '0.00';
        }

        return number_format((float) $cleaned, 2, '.', '');
    }
}