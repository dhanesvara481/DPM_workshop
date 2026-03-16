<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StokOpname;
use App\Models\DetailStokOpname;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\RiwayatStok;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StokOpnameController extends Controller
{
    // =========================================================================
    // ADMIN — Kelola sesi opname
    // =========================================================================

    // ── 1. Daftar semua sesi (admin) ──────────────────────────────────────────

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

    // ── 2. Form buat sesi opname baru (admin) ─────────────────────────────────

    public function buatOpname()
    {
        $draftAktif = StokOpname::whereIn('status', ['draft', 'menunggu_approval'])
                        ->latest()
                        ->first();

        $barangs = Barang::orderBy('kode_barang')->get();

        $staffList = User::where('role', 'staff')
                         ->where('status', 'aktif')
                         ->orderBy('username')
                         ->get(['user_id', 'username']);

        return view('admin.stok_opname.tambah_stok_opname', compact('barangs', 'draftAktif', 'staffList'));
    }

    // ── 3. Simpan sesi opname baru (admin) ────────────────────────────────────

    public function simpanOpname(Request $request)
    {
        $request->validate([
            'tanggal_opname' => 'required|date',
            'keterangan'     => 'nullable|string|max:255',
            'assigned_to'    => 'nullable|exists:user,user_id',
        ]);

        DB::transaction(function () use ($request) {
            $user = Auth::user();

            $assignedTo               = $request->assigned_to ?: null;
            $assigneeUsernameSnapshot = null;

            if ($assignedTo) {
                $assignee                 = User::find($assignedTo);
                $assigneeUsernameSnapshot = $assignee?->username;
            }

            $opname = StokOpname::create([
                'user_id'                    => $user->user_id,
                'tanggal_opname'             => $request->tanggal_opname,
                'keterangan'                 => $request->keterangan,
                'status'                     => 'draft',
                'username_snapshot'          => $user->username,
                'email_snapshot'             => $user->email,
                'assigned_to'                => $assignedTo,
                'assignee_username_snapshot' => $assigneeUsernameSnapshot,
            ]);

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
            ->with('success', 'Sesi stok opname berhasil dibuat.');
    }

    // ── 4. Form edit stok fisik oleh ADMIN (hanya jika tidak ada assignee) ────

    public function ubahOpname(int $id)
    {
        $opname = StokOpname::with('details')->findOrFail($id);

        if (!$opname->isDraft()) {
            return redirect()
                ->route('stok_opname.detailOpname', $id)
                ->with('error', 'Sesi ini tidak bisa diedit karena statusnya: ' . $opname->status_label);
        }

        if ($opname->isAssigned()) {
            return redirect()
                ->route('stok_opname.detailOpname', $id)
                ->with('error', 'Sesi ini di-assign ke ' . $opname->nama_assignee . '. Hanya staff tersebut yang bisa mengisi stok fisik.');
        }

        return view('admin.stok_opname.ubah_stok_opname', compact('opname'));
    }

    // ── 5. Simpan input stok fisik oleh ADMIN ─────────────────────────────────

    public function updateOpname(Request $request, int $id)
    {
        $opname = StokOpname::with('details')->findOrFail($id);

        if (!$opname->isDraft()) {
            return back()->with('error', 'Sesi tidak bisa diubah.');
        }

        if ($opname->isAssigned()) {
            return back()->with('error', 'Sesi ini di-assign ke staff. Admin tidak bisa mengisi stok fisik.');
        }

        $request->validate([
            'items'              => 'required|array',
            'items.*.detail_id'  => 'required|integer',
            'items.*.stok_fisik' => 'nullable|integer|min:0',
            'items.*.keterangan' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $opname) {
            foreach ($request->items as $item) {
                $detail = DetailStokOpname::where('opname_id', $opname->opname_id)
                            ->where('detail_opname_id', (int) $item['detail_id'])
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

    // ── 6. Submit untuk approval oleh ADMIN (jika tidak ada assignee) ─────────

    public function submitOpname(int $id)
    {
        $opname = StokOpname::with('details')->findOrFail($id);

        if (!$opname->isDraft()) {
            return back()->with('error', 'Hanya sesi berstatus draft yang bisa disubmit.');
        }

        if ($opname->isAssigned()) {
            return back()->with('error', 'Sesi ini harus disubmit oleh staff yang di-assign (' . $opname->nama_assignee . ').');
        }

        $belumDiisi = $opname->details->filter(fn($d) => is_null($d->stok_fisik))->count();

        if ($belumDiisi > 0) {
            return back()->with('error', "Masih ada {$belumDiisi} barang yang belum diisi stok fisiknya.");
        }

        $opname->update(['status' => 'menunggu_approval']);

        return redirect()
            ->route('stok_opname.detailOpname', $id)
            ->with('success', 'Sesi opname berhasil disubmit. Menunggu persetujuan.');
    }

    // ── 7. Detail / tampilan sesi (admin) ─────────────────────────────────────

    public function detailOpname(int $id, Request $request)
    {
        $opname = StokOpname::with('details')->findOrFail($id);

        $tampilkanSelisih = $request->boolean('hanya_selisih', false);

        $detailsQuery = $tampilkanSelisih
            ? $opname->details->filter(fn($d) => $d->has_selisih)
            : $opname->details;

        $perPage     = 10;
        $currentPage = max(1, (int) $request->input('page', 1));
        $total       = $detailsQuery->count();
        $lastPage    = max(1, (int) ceil($total / $perPage));
        $currentPage = min($currentPage, $lastPage);

        $details = new \Illuminate\Pagination\LengthAwarePaginator(
            $detailsQuery->slice(($currentPage - 1) * $perPage, $perPage)->values(),
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $totalItem  = $opname->details->count();
        $sudahDiisi = $opname->details->filter(fn($d) => !is_null($d->stok_fisik))->count();
        $adaSelisih = $opname->details->filter(fn($d) => $d->has_selisih)->count();
        $balance    = $opname->details->filter(fn($d) => !is_null($d->stok_fisik) && $d->selisih === 0)->count();

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
            'opname', 'details', 'riwayatSelisih', 'tampilkanSelisih',
            'totalItem', 'sudahDiisi', 'adaSelisih', 'balance'
        ));
    }

    // ── 8. Approve (admin) ────────────────────────────────────────────────────

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
            $waktuAdjust = now();

            foreach ($opname->details as $detail) {
                if ($detail->selisih === 0 || is_null($detail->selisih)) {
                    $detail->update(['item_status' => 'balance']);
                    continue;
                }

                $barang = $detail->barang_id ? Barang::find($detail->barang_id) : null;

                if (!$barang) {
                    $detail->update(['item_status' => 'adjusted']);
                    continue;
                }

                $selisih  = (int) $detail->selisih;
                $stokLama = (int) $barang->stok;
                $stokBaru = (int) $detail->stok_fisik;

                $riwayatTerakhir = RiwayatStok::where('barang_id', $barang->barang_id)
                    ->orderBy('tanggal_riwayat_stok', 'desc')
                    ->orderBy('riwayat_stok_id', 'desc')
                    ->first();

                $stokAwal  = $riwayatTerakhir ? (int) $riwayatTerakhir->stok_akhir : $stokLama;
                $stokAkhir = $stokBaru;

                $barang->update(['stok' => (string) $stokBaru]);

                $barangMasukId  = null;
                $barangKeluarId = null;

                if ($selisih > 0) {
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

                $detail->update(['item_status' => 'adjusted']);
            }

            $opname->update([
                'status'                     => 'disetujui',
                'approved_by'                => $approver->user_id,
                'approver_username_snapshot' => $approver->username,
                'approved_at'                => $waktuAdjust,
                'catatan_approval'           => $request->catatan_approval,
            ]);
        });

        return redirect()
            ->route('stok_opname.detailOpname', $id)
            ->with('success', 'Stok opname berhasil disetujui. Semua selisih telah disesuaikan.');
    }

    // ── 9. Tolak (admin) ──────────────────────────────────────────────────────

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

    // ── 10. Hapus sesi (admin — hanya draft/ditolak) ──────────────────────────

    public function hapusOpname(int $id)
    {
        $opname = StokOpname::findOrFail($id);

        if (!in_array($opname->status, ['draft', 'ditolak'])) {
            return back()->with('error', 'Hanya sesi draft atau ditolak yang bisa dihapus.');
        }

        $opname->delete();

        return redirect()
            ->route('stok_opname.daftarOpname')
            ->with('success', 'Sesi opname berhasil dihapus.');
    }

    // =========================================================================
    // STAFF — Input stok fisik & submit
    // =========================================================================

    // ── 11. Daftar opname yang di-assign ke staff ini ─────────────────────────

    public function daftarOpnameStaff(Request $request)
    {
        $user = Auth::user();

        $sortable = ['tanggal_opname', 'status', 'created_at'];
        $sort     = in_array($request->sort, $sortable) ? $request->sort : 'created_at';
        $dir      = $request->dir === 'asc' ? 'asc' : 'desc';

        $opnames = StokOpname::withCount([
                        'details',
                        'details as jumlah_selisih_count' => fn($q) =>
                            $q->whereColumn('stok_fisik', '!=', 'stok_sistem')
                              ->whereNotNull('stok_fisik'),
                        'details as sudah_diisi_count' => fn($q) =>
                            $q->whereNotNull('stok_fisik'),
                    ])
                    ->where('assigned_to', $user->user_id)
                    ->when($request->status, fn($q) => $q->where('status', $request->status))
                    ->orderBy($sort, $dir)
                    ->paginate(10)
                    ->withQueryString();

        return view('staff.stok_opname.tampilan_opname_staff', compact('opnames', 'sort', 'dir'));
    }

    // ── 12. Form input stok fisik oleh STAFF ──────────────────────────────────

    public function ubahOpnameStaff(int $id)
    {
        $user   = Auth::user();
        $opname = StokOpname::with('details')->findOrFail($id);

        // FIX: cast ke int di kedua sisi — DB bisa return string
        if ((int) $opname->assigned_to !== (int) $user->user_id) {
            return redirect()
                ->route('stok_opname.daftarOpnameStaff')
                ->with('error', 'Kamu tidak memiliki akses ke sesi opname ini.');
        }

        if (!$opname->isDraft()) {
            return redirect()
                ->route('stok_opname.detailOpnameStaff', $id)
                ->with('error', 'Sesi ini tidak bisa diedit karena statusnya: ' . $opname->status_label);
        }

        return view('staff.stok_opname.ubah_opname_staff', compact('opname'));
    }

    // ── 13. Simpan input stok fisik oleh STAFF ────────────────────────────────

    public function updateOpnameStaff(Request $request, int $id)
    {
        $user   = Auth::user();
        $opname = StokOpname::with('details')->findOrFail($id);

        // FIX: cast ke int di kedua sisi
        if ((int) $opname->assigned_to !== (int) $user->user_id) {
            return back()->with('error', 'Kamu tidak memiliki akses ke sesi opname ini.');
        }

        if (!$opname->isDraft()) {
            return back()->with('error', 'Sesi tidak bisa diubah.');
        }

        $request->validate([
            'items'              => 'required|array',
            'items.*.detail_id'  => 'required|integer',
            'items.*.stok_fisik' => 'nullable|integer|min:0',
            'items.*.keterangan' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $opname) {
            foreach ($request->items as $item) {
                $detail = DetailStokOpname::where('opname_id', $opname->opname_id)
                            ->where('detail_opname_id', (int) $item['detail_id'])
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

    // ── 14. Submit untuk approval oleh STAFF ──────────────────────────────────
    // Alur JS di view: tombol "Submit" → save draft dulu (updateOpnameStaff)
    // → setelah response sukses baru submit form ini ke endpoint ini.
    // Jadi saat method ini dipanggil, semua data sudah tersimpan di DB.

    public function submitOpnameStaff(int $id)
    {
        $user   = Auth::user();
        // Reload fresh dari DB agar details yang baru di-save terbaca
        $opname = StokOpname::with('details')->findOrFail($id);

        // FIX: cast ke int di kedua sisi
        if ((int) $opname->assigned_to !== (int) $user->user_id) {
            return back()->with('error', 'Kamu tidak memiliki akses ke sesi opname ini.');
        }

        if (!$opname->isDraft()) {
            return back()->with('error', 'Hanya sesi berstatus draft yang bisa disubmit.');
        }

        $belumDiisi = $opname->details->filter(fn($d) => is_null($d->stok_fisik))->count();

        if ($belumDiisi > 0) {
            return back()->with('error', "Masih ada {$belumDiisi} barang yang belum diisi stok fisiknya.");
        }

        $opname->update(['status' => 'menunggu_approval']);

        return redirect()
            ->route('stok_opname.detailOpnameStaff', $id)
            ->with('success', 'Berhasil disubmit! Menunggu persetujuan admin.');
    }

    // ── 15. Detail opname (view-only untuk staff) ─────────────────────────────

    public function detailOpnameStaff(int $id, Request $request)
    {
        $user   = Auth::user();
        $opname = StokOpname::with('details')->findOrFail($id);

        // FIX: cast ke int di kedua sisi
        if ((int) $opname->assigned_to !== (int) $user->user_id) {
            return redirect()
                ->route('stok_opname.daftarOpnameStaff')
                ->with('error', 'Kamu tidak memiliki akses ke sesi opname ini.');
        }

        $tampilkanSelisih = $request->boolean('hanya_selisih', false);

        $detailsQuery = $tampilkanSelisih
            ? $opname->details->filter(fn($d) => $d->has_selisih)
            : $opname->details;

        $perPage     = 10;
        $currentPage = max(1, (int) $request->input('page', 1));
        $total       = $detailsQuery->count();
        $lastPage    = max(1, (int) ceil($total / $perPage));
        $currentPage = min($currentPage, $lastPage);

        $details = new \Illuminate\Pagination\LengthAwarePaginator(
            $detailsQuery->slice(($currentPage - 1) * $perPage, $perPage)->values(),
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $totalItem  = $opname->details->count();
        $sudahDiisi = $opname->details->filter(fn($d) => !is_null($d->stok_fisik))->count();
        $adaSelisih = $opname->details->filter(fn($d) => $d->has_selisih)->count();
        $balance    = $opname->details->filter(fn($d) => !is_null($d->stok_fisik) && $d->selisih === 0)->count();

        return view('staff.stok_opname.detail_opname_staff', compact(
            'opname', 'details', 'tampilkanSelisih',
            'totalItem', 'sudahDiisi', 'adaSelisih', 'balance'
        ));
    }
}