<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    // ─── Tampilan utama + riwayat ───────────────────────────────────────────────

    /**
     * GET /barang_keluar
     * Tampilkan form input + riwayat barang keluar.
     */
    public function getBarangKeluar(Request $request)
    {
        // Dropdown: semua barang yang masih punya stok
        $barangs = Barang::orderBy('kode_barang')->get();

        // Riwayat: join ke tabel barang untuk ambil kode & nama
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

    // ─── Simpan ────────────────────────────────────────────────────────────────

    /**
     * POST /barang_keluar/store
     * Validasi, kurangi stok, simpan transaksi keluar.
     */
    public function simpanBarangKeluar(Request $request)
    {
        // ── 1. Validasi input ────────────────────────────────────────────────
        $validated = $request->validate([
            'barang_id'   => ['required', 'exists:barang,barang_id'],
            'qty_keluar'  => ['required', 'integer', 'min:1'],
            'tanggal'     => ['required', 'date'],
            'keterangan'  => ['required', 'in:Barang Rusak,Barang Dikembalikan,Penyesuaian Stok'],
        ], [
            'barang_id.required'  => 'Pilih kode barang terlebih dahulu.',
            'barang_id.exists'    => 'Barang tidak ditemukan.',
            'qty_keluar.required' => 'Jumlah keluar wajib diisi.',
            'qty_keluar.min'      => 'Jumlah keluar minimal 1.',
            'tanggal.required'    => 'Tanggal wajib diisi.',
            'keterangan.required' => 'Pilih keterangan.',
            'keterangan.in'       => 'Keterangan tidak valid.',
        ]);

        // ── 2. Cek stok cukup (pessimistic lock) ────────────────────────────
        $barang = Barang::where('barang_id', $validated['barang_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

        if ((int) $barang->stok < (int) $validated['qty_keluar']) {
            return back()
                ->withInput()
                ->withErrors(['qty_keluar' => 'Jumlah keluar ('.$validated['qty_keluar'].') melebihi stok tersedia ('.$barang->stok.').']);
        }

        // ── 3. Simpan dalam transaksi DB ────────────────────────────────────
        DB::transaction(function () use ($validated, $barang) {
            // Kurangi stok
            $barang->decrement('stok', (int) $validated['qty_keluar']);

            // Simpan riwayat keluar
            BarangKeluar::create([
                'user_id'       => Auth::id(),
                'barang_id'     => $validated['barang_id'],
                'jumlah_keluar' => $validated['qty_keluar'],
                'tanggal_keluar'=> $validated['tanggal'],
                'keterangan'    => $validated['keterangan'],
                'ref_invoice'   => null,
            ]);
        });

        return redirect()
            ->route('barang_keluar')
            ->with('success', 'Stok keluar berhasil dicatat.');
    }

    // ─── Tidak digunakan (resource stub) ───────────────────────────────────────

    public function create()  {}
    public function show(string $id)  {}
    public function edit(string $id)  {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}