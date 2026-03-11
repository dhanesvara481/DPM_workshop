<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StokOpname;
use App\Models\DetailStokOpname;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StokOpnameController extends Controller
{
    // ── 1. Daftar semua sesi opname ──────────────────────────────────────────

    public function daftarOpname(Request $request)
    {
        $sortable = ['tanggal_opname', 'status', 'created_at'];
        $sort     = in_array($request->sort, $sortable) ? $request->sort : 'created_at';
        $dir      = $request->dir === 'asc' ? 'asc' : 'desc';

        $opnames = StokOpname::withCount([
                        'details',
                        'details as jumlah_selisih_count' => fn($q) =>
                            $q->whereColumn('stok_fisik', '!=', 'stok_sistem')
                              ->whereNotNull('stok_fisik'),
                    ])
                    ->when($request->status, fn($q) => $q->where('status', $request->status))
                    ->when($request->dari,   fn($q) => $q->whereDate('tanggal_opname', '>=', $request->dari))
                    ->when($request->sampai, fn($q) => $q->whereDate('tanggal_opname', '<=', $request->sampai))
                    ->orderBy($sort, $dir)
                    ->paginate(10)
                    ->withQueryString();

        return view('admin.stok_opname.tampilan_stok_opname', compact('opnames', 'sort', 'dir'));
    }

    // ── 2. Form buat sesi opname baru ────────────────────────────────────────

    public function buatOpname()
    {
        // Cek apakah ada sesi draft yang belum selesai
        $draftAktif = StokOpname::where('status', 'draft')
                        ->orWhere('status', 'menunggu_approval')
                        ->latest()
                        ->first();

        $barangs = Barang::orderBy('kode_barang')->get();

        return view('admin.stok_opname.tambah_stok_opname', compact('barangs', 'draftAktif'));
    }

    // ── 3. Simpan sesi opname baru (hanya header + snapshot semua barang) ────

    public function simpanOpname(Request $request)
    {
        $request->validate([
            'tanggal_opname' => 'required|date',
            'keterangan'     => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $user = Auth::user();

            $opname = StokOpname::create([
                'user_id'            => $user->user_id,
                'tanggal_opname'     => $request->tanggal_opname,
                'keterangan'         => $request->keterangan,
                'status'             => 'draft',
                'username_snapshot'  => $user->username,
                'email_snapshot'     => $user->email,
            ]);

            // Snapshot semua barang beserta stok sistem saat ini
            $barangs = Barang::orderBy('kode_barang')->get();

            foreach ($barangs as $barang) {
                DetailStokOpname::create([
                    'opname_id'            => $opname->opname_id,
                    'barang_id'            => $barang->barang_id,
                    'kode_barang_snapshot' => $barang->kode_barang,
                    'nama_barang_snapshot' => $barang->nama_barang,
                    'satuan_snapshot'      => $barang->satuan,
                    'stok_sistem'          => (int) $barang->stok,
                    'stok_fisik'           => null,
                    'selisih'              => null,
                    'item_status'          => 'pending',
                ]);
            }
        });

        return redirect()
            ->route('stok_opname.daftarOpname')
            ->with('success', 'Sesi stok opname berhasil dibuat. Silakan isi stok fisik.');
    }

    // ── 4. Form input stok fisik (edit) ──────────────────────────────────────

    public function ubahOpname(int $id)
    {
        $opname = StokOpname::with('details')->findOrFail($id);

        // Hanya bisa edit saat masih draft
        if (!$opname->isDraft()) {
            return redirect()
                ->route('stok_opname.detailOpname', $id)
                ->with('error', 'Sesi ini tidak bisa diedit karena statusnya: ' . $opname->status_label);
        }

        return view('admin.stok_opname.ubah_stok_opname', compact('opname'));
    }

    // ── 5. Simpan input stok fisik (update detail) ───────────────────────────

    public function updateOpname(Request $request, int $id)
    {
        $opname = StokOpname::with('details')->findOrFail($id);

        if (!$opname->isDraft()) {
            return back()->with('error', 'Sesi tidak bisa diubah.');
        }

        $request->validate([
            'items'             => 'required|array',
            'items.*.detail_id' => 'required|integer',
            'items.*.stok_fisik'=> 'nullable|integer|min:0',
            'items.*.keterangan'=> 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $opname) {
            foreach ($request->items as $item) {
                $detail = DetailStokOpname::where('opname_id', $opname->opname_id)
                            ->where('detail_opname_id', $item['detail_id'])
                            ->first();

                if (!$detail) continue;

                $stokFisik = isset($item['stok_fisik']) && $item['stok_fisik'] !== ''
                    ? (int) $item['stok_fisik']
                    : null;

                $selisih = !is_null($stokFisik)
                    ? $stokFisik - $detail->stok_sistem
                    : null;

                $detail->update([
                    'stok_fisik'  => $stokFisik,
                    'selisih'     => $selisih,
                    'keterangan'  => $item['keterangan'] ?? null,
                    'item_status' => 'pending',
                ]);
            }
        });

        return back()->with('success', 'Data stok fisik berhasil disimpan.');
    }

    // ── 6. Submit untuk approval (draft → menunggu_approval) ─────────────────

    public function submitOpname(int $id)
    {
        $opname = StokOpname::with('details')->findOrFail($id);

        if (!$opname->isDraft()) {
            return back()->with('error', 'Hanya sesi berstatus draft yang bisa disubmit.');
        }

        // Cek semua stok fisik sudah diisi
        $belumDiisi = $opname->details->filter(fn($d) => is_null($d->stok_fisik))->count();

        if ($belumDiisi > 0) {
            return back()->with('error', "Masih ada {$belumDiisi} barang yang belum diisi stok fisiknya.");
        }

        $opname->update(['status' => 'menunggu_approval']);

        return redirect()
            ->route('stok_opname.detailOpname', $id)
            ->with('success', 'Sesi opname berhasil disubmit. Menunggu persetujuan.');
    }

    // ── 7. Detail / tampilan sesi opname ─────────────────────────────────────

    public function detailOpname(int $id, Request $request)
    {
        $opname = StokOpname::with('details')->findOrFail($id);

        // Filter tampilan: semua / hanya_selisih
        $tampilkanSelisih = $request->boolean('hanya_selisih', false);

        $details = $tampilkanSelisih
            ? $opname->details->filter(fn($d) => $d->has_selisih)
            : $opname->details;

        // Rekap riwayat stok per barang yang selisih (untuk cross-check)
        $riwayatSelisih = [];
        if ($opname->isMenungguApproval() || $opname->isDisetujui()) {
            $barangIdSelisih = $opname->details
                ->filter(fn($d) => $d->has_selisih && $d->barang_id)
                ->pluck('barang_id');

            if ($barangIdSelisih->isNotEmpty()) {
                $riwayatSelisih = RiwayatStok::whereIn('barang_id', $barangIdSelisih)
                    ->whereDate('tanggal_riwayat_stok', '<=', $opname->tanggal_opname)
                    ->orderBy('tanggal_riwayat_stok', 'desc')
                    ->orderBy('riwayat_stok_id', 'desc')
                    ->get()
                    ->groupBy('barang_id');
            }
        }

        return view('admin.stok_opname.detail_stok_opname', compact(  
            'opname', 'details', 'riwayatSelisih', 'tampilkanSelisih'
        ));
    }

    // ── 8. Approve — update stok, catat riwayat ──────────────────────────────

    public function setujuiOpname(Request $request, int $id)
    {
        $request->validate([
            'catatan_approval' => 'nullable|string|max:255',
        ]);

        $opname = StokOpname::with('details')->findOrFail($id);

        if (!$opname->isMenungguApproval()) {
            return back()->with('error', 'Hanya sesi yang menunggu approval bisa disetujui.');
        }

        DB::transaction(function () use ($request, $opname) {
            $approver    = Auth::user();
            $tanggalHari = $opname->tanggal_opname->toDateString();
            $waktuAdjust = now();

            foreach ($opname->details as $detail) {
                // Tidak ada selisih → tandai balance, skip
                if ($detail->selisih === 0 || is_null($detail->selisih)) {
                    $detail->update(['item_status' => 'balance']);
                    continue;
                }

                $barang = $detail->barang_id ? Barang::find($detail->barang_id) : null;

                if (!$barang) {
                    // Barang sudah dihapus, skip adjust stok tapi tandai adjusted
                    $detail->update(['item_status' => 'adjusted']);
                    continue;
                }

                $selisih    = (int) $detail->selisih;
                $stokLama   = (int) $barang->stok;
                $stokBaru   = $detail->stok_fisik; // stok disesuaikan ke fisik

                // ── Hitung stok_awal & stok_akhir untuk riwayat ──────────────
                $riwayatTerakhir = RiwayatStok::where('barang_id', $barang->barang_id)
                    ->orderBy('tanggal_riwayat_stok', 'desc')
                    ->orderBy('riwayat_stok_id', 'desc')
                    ->first();

                $stokAwal  = $riwayatTerakhir ? (int) $riwayatTerakhir->stok_akhir : $stokLama;
                $stokAkhir = $stokBaru;

                // ── Update stok barang ────────────────────────────────────────
                $barang->update(['stok' => (string) $stokBaru]);

                // ── Catat ke barang_masuk / barang_keluar ─────────────────────
                $barangMasukId  = null;
                $barangKeluarId = null;

                if ($selisih > 0) {
                    // Stok fisik LEBIH dari sistem → barang masuk (penyesuaian)
                    $barangMasuk = BarangMasuk::create([
                        'barang_id'            => $barang->barang_id,
                        'user_id'              => $approver->user_id,
                        'jumlah_masuk'         => abs($selisih),
                        'tanggal_masuk'        => $waktuAdjust,
                        'kode_barang_snapshot' => $detail->kode_barang_snapshot,
                        'nama_barang_snapshot' => $detail->nama_barang_snapshot,
                        'satuan_snapshot'      => $detail->satuan_snapshot,
                        'username_snapshot'    => $approver->username,
                        'email_snapshot'       => $approver->email,
                    ]);
                    $barangMasukId = $barangMasuk->barang_masuk_id;

                } else {
                    // Stok fisik KURANG dari sistem → barang keluar (penyesuaian)
                    $barangKeluar = BarangKeluar::create([
                        'user_id'              => $approver->user_id,
                        'barang_id'            => $barang->barang_id,
                        'jumlah_keluar'        => abs($selisih),
                        'tanggal_keluar'       => $waktuAdjust,
                        'keterangan'           => 'Penyesuaian Stok',
                        'ref_invoice'          => null,
                        'kode_barang_snapshot' => $detail->kode_barang_snapshot,
                        'nama_barang_snapshot' => $detail->nama_barang_snapshot,
                        'satuan_snapshot'      => $detail->satuan_snapshot,
                        'username_snapshot'    => $approver->username,
                        'email_snapshot'       => $approver->email,
                    ]);
                    $barangKeluarId = $barangKeluar->barang_keluar_id;
                }

                // ── Catat riwayat stok ────────────────────────────────────────
                RiwayatStok::create([
                    'barang_id'            => $barang->barang_id,
                    'user_id'              => $approver->user_id,
                    'barang_masuk_id'      => $barangMasukId,
                    'barang_keluar_id'     => $barangKeluarId,
                    'tanggal_riwayat_stok' => $waktuAdjust,
                    'stok_awal'            => $stokAwal,
                    'stok_akhir'           => $stokAkhir,
                    'kode_barang_snapshot' => $detail->kode_barang_snapshot,
                    'nama_barang_snapshot' => $detail->nama_barang_snapshot,
                    'username_snapshot'    => $approver->username,
                    'email_snapshot'       => $approver->email,
                ]);

                // ── Update status item ────────────────────────────────────────
                $detail->update(['item_status' => 'adjusted']);
            }

            // ── Update header opname ──────────────────────────────────────────
            $opname->update([
                'status'                    => 'disetujui',
                'approved_by'               => $approver->user_id,
                'approver_username_snapshot'=> $approver->username,
                'approved_at'               => $waktuAdjust,
                'catatan_approval'          => $request->catatan_approval,
            ]);
        });

        return redirect()
            ->route('stok_opname.detailOpname', $id)
            ->with('success', 'Stok opname berhasil disetujui. Semua selisih telah disesuaikan.');
    }

    // ── 9. Tolak opname ───────────────────────────────────────────────────────

    public function tolakOpname(Request $request, int $id)
    {
        $request->validate([
            'catatan_approval' => 'required|string|max:255',
        ], [
            'catatan_approval.required' => 'Catatan wajib diisi saat menolak opname.',
        ]);

        $opname = StokOpname::findOrFail($id);

        if (!$opname->isMenungguApproval()) {
            return back()->with('error', 'Hanya sesi yang menunggu approval bisa ditolak.');
        }

        $approver = Auth::user();

        $opname->update([
            'status'                     => 'ditolak',
            'approved_by'                => $approver->user_id,
            'approver_username_snapshot' => $approver->username,
            'approved_at'                => now(),
            'catatan_approval'           => $request->catatan_approval,
        ]);

        return redirect()
            ->route('stok_opname.detailOpname', $id)
            ->with('info', 'Sesi opname telah ditolak.');
    }

    // ── 10. Hapus sesi (hanya draft / ditolak) ───────────────────────────────

    public function hapusOpname(int $id)
    {
        $opname = StokOpname::findOrFail($id);

        if (!in_array($opname->status, ['draft', 'ditolak'])) {
            return back()->with('error', 'Hanya sesi draft atau ditolak yang bisa dihapus.');
        }

        $opname->delete(); // cascade → detail_stok_opname ikut terhapus

        return redirect()
            ->route('stok_opname.daftarOpname')
            ->with('success', 'Sesi opname berhasil dihapus.');
    }
}