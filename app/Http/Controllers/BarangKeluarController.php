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
    public function getBarangMasuk(Request $request)
    {
        $barangs = Barang::orderBy('kode_barang', 'asc')->get();

        $sortable = ['tanggal_masuk', 'kode_barang', 'nama_barang', 'jumlah_masuk'];
        $sort     = in_array($request->sort, $sortable) ? $request->sort : 'tanggal_masuk';
        $dir      = $request->dir === 'asc' ? 'asc' : 'desc';

        $barangMasuk = BarangMasuk::leftJoin('barang', 'barang_masuk.barang_id', '=', 'barang.barang_id')
            ->select(
                'barang_masuk.*',
                DB::raw("COALESCE(barang_masuk.kode_barang_snapshot, barang.kode_barang, '[Dihapus]') as kode_barang"),
                DB::raw("COALESCE(barang_masuk.nama_barang_snapshot, barang.nama_barang, '[Barang Dihapus]') as nama_barang"),
                DB::raw("COALESCE(barang_masuk.satuan_snapshot, barang.satuan, '-') as satuan"),
                DB::raw("COALESCE(barang.stok, '-') as stok"),
                // ── Tampilkan nama pengguna: snapshot dulu, fallback ke relasi live
                DB::raw("COALESCE(barang_masuk.username_snapshot, '[User Dihapus]') as nama_pengguna"),
            )
            ->when($request->search, function ($q) use ($request) {
                $s = $request->search;
                $q->where('barang.kode_barang', 'like', "%$s%")
                  ->orWhere('barang.nama_barang', 'like', "%$s%");
            })
            ->orderBy($sort === 'kode_barang' || $sort === 'nama_barang' ? 'barang.'.$sort : 'barang_masuk.'.$sort, $dir)
            ->paginate(10)
            ->withQueryString();

        return view('admin.barang_masuk', [
            'barangs'     => $barangs,
            'barangMasuk' => $barangMasuk,
            'sort'        => $sort,
            'dir'         => $dir,
        ]);
    }

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
                'tanggal.datetime'   => 'Format tanggal tidak valid.',
            ]);

            DB::beginTransaction();

            $barang = Barang::where('barang_id', $validated['barang_id'])->firstOrFail();

            $userId = Auth::id();
            if (!$userId) {
                throw new \Exception('User tidak terautentikasi. Silakan login terlebih dahulu.');
            }

            // ── Ambil data user saat ini untuk snapshot ───────────────────────
            $currentUser = Auth::user();

            $tanggalSaja  = $validated['tanggal'];
            $tanggalInput = $tanggalSaja . ' ' . now()->format('H:i:s');
            $qty          = (int) $validated['qty_masuk'];
            
            $riwayatHariIniPertama = RiwayatStok::where('barang_id', $validated['barang_id'])
                ->whereDate('tanggal_riwayat_stok', $tanggalSaja)
                ->orderBy('riwayat_stok_id', 'asc')
                ->first();
            
            $riwayatHariIniTerakhir = RiwayatStok::where('barang_id', $validated['barang_id'])
                ->whereDate('tanggal_riwayat_stok', $tanggalSaja)
                ->orderBy('riwayat_stok_id', 'desc')
                ->first();
            
            $riwayatSebelumnya = RiwayatStok::where('barang_id', $validated['barang_id'])
                ->whereDate('tanggal_riwayat_stok', '<', $tanggalSaja)
                ->orderBy('tanggal_riwayat_stok', 'desc')
                ->orderBy('riwayat_stok_id', 'desc')
                ->first();

            if ($riwayatHariIniPertama) {
                $stokAwal  = (int) $riwayatHariIniPertama->stok_awal;
                $stokAkhir = (int) $riwayatHariIniTerakhir->stok_akhir + $qty;
            } elseif ($riwayatSebelumnya) {
                $stokAwal  = (int) $riwayatSebelumnya->stok_akhir;
                $stokAkhir = $stokAwal + $qty;
            } else {
                $stokAwal  = $qty;
                $stokAkhir = $qty;
            }

            $barangMasuk = BarangMasuk::create([
                'barang_id'            => $validated['barang_id'],
                'user_id'              => $userId,
                'jumlah_masuk'         => $qty,
                'tanggal_masuk'        => $tanggalInput,
                'kode_barang_snapshot' => $barang->kode_barang,
                'nama_barang_snapshot' => $barang->nama_barang,
                'satuan_snapshot'      => $barang->satuan,
                // ── Snapshot user saat input ──────────────────────────────────
                'username_snapshot'    => $currentUser->username,
                'email_snapshot'       => $currentUser->email,
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
                'kode_barang_snapshot' => $barang->kode_barang,
                'nama_barang_snapshot' => $barang->nama_barang,
                // ── Snapshot user ─────────────────────────────────────────────
                'username_snapshot'    => $currentUser->username,
                'email_snapshot'       => $currentUser->email,
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