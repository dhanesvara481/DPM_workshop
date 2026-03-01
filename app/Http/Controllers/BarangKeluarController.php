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

        $barangKeluar = BarangKeluar::join('barang', 'barang.barang_id', '=', 'barang_keluar.barang_id')
            ->select(
                'barang_keluar.*',
                'barang.kode_barang',
                'barang.nama_barang',
                DB::raw("DATE_FORMAT(barang_keluar.tanggal_keluar, '%d-%m-%Y %H:%i:%s') as tanggal"),
                'barang_keluar.jumlah_keluar as qty_keluar',
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
        // FIX: Enum keterangan hanya untuk input manual (tanpa 'Invoice' - itu dari sistem)
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
             $tanggalInput = $validated['tanggal']; // buat whereDate
             $waktuKeluar  = now();                 // ini yg bikin jam kebaca
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
                'user_id'        => Auth::id(),
                'barang_id'      => $validated['barang_id'],
                'jumlah_keluar'  => $qty,
                'tanggal_keluar' => $waktuKeluar,
                'keterangan'     => $validated['keterangan'], // manual input: Barang Rusak, dll.
                'ref_invoice'    => null,
            ]);

            RiwayatStok::create([
                'barang_id'            => $validated['barang_id'],
                'user_id'              => Auth::id(),
                'barang_masuk_id'      => null,
                'barang_keluar_id'     => $barangKeluar->barang_keluar_id,
                'tanggal_riwayat_stok' => $waktuKeluar,
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