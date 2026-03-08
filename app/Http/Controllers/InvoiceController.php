<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\DetailInvoice;
use App\Models\Barang;
use App\Models\RiwayatTransaksi;
use App\Models\BarangKeluar;
use App\Models\RiwayatStok;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    // ── Admin ────────────────────────────────────────────────────────────────

    public function getTampilanInvoice()
    {
        $barangs = Barang::whereRaw('CAST(stok AS UNSIGNED) > 0')
            ->orderBy('barang_id')
            ->get(['barang_id', 'kode_barang', 'nama_barang', 'satuan', 'harga_jual', 'stok']);

        return view('admin.invoice.tampilan_invoice', compact('barangs'));
    }

    public function getTampilanKonfirmasi(Request $request)
    {
        $q    = $request->input('q', '');
        $from = $request->input('from');
        $to   = $request->input('to');

        $allowedSorts = ['invoice_id', 'subtotal', 'status', 'tanggal_invoice'];
        $sort = in_array($request->input('sort'), $allowedSorts)
            ? $request->input('sort')
            : 'tanggal_invoice';
        $dir  = $request->input('dir') === 'asc' ? 'asc' : 'desc';

        $query = Invoice::with(['items' => fn($q) => $q->limit(1)])
            ->orderBy($sort, $dir);

        if ($from) $query->whereDate('tanggal_invoice', '>=', $from);
        if ($to)   $query->whereDate('tanggal_invoice', '<=', $to);

        if ($q) {
            $query->where(function ($qb) use ($q) {
                $numericId = ltrim(str_ireplace('INV-', '', $q), '0');
                if (is_numeric($numericId)) {
                    $qb->orWhere('invoice_id', $numericId);
                }
                $qb->orWhereHas('items', fn($qi) =>
                    $qi->where('nama_pelanggan', 'like', "%{$q}%")
                );
            });
        }

        $invoices     = $query->paginate(15)->withQueryString();
        $pendingCount = Invoice::where('status', 'Pending')->count();

        return view('admin.invoice.konfirmasi_invoice', compact('invoices', 'q', 'pendingCount', 'sort', 'dir'));
    }

    public function tandaKonfirmasi(Request $request, $invoice)
    {
        $inv = Invoice::findOrFail($invoice);

        if ($inv->status === 'Paid') {
            return back()->with('error', 'Invoice sudah berstatus Paid.');
        }

        DB::beginTransaction();
        try {
            $inv->update(['status' => 'Paid', 'tanggal_bayar' => now()]);
            $this->prosesStokDariInvoice($inv);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Invoice INV-' . $inv->invoice_id . ' berhasil dikonfirmasi sebagai Paid.');
    }

    // ── Staff ────────────────────────────────────────────────────────────────

    public function getTampilanInvoiceStaff()
    {
        $barangs = Barang::whereRaw('CAST(stok AS UNSIGNED) > 0')
            ->orderBy('barang_id')
            ->get(['barang_id', 'kode_barang', 'nama_barang', 'satuan', 'harga_jual', 'stok']);

        return view('staff.invoice.tampilan_invoice_staff', compact('barangs'));
    }

    // ── Shared ───────────────────────────────────────────────────────────────

    public function checkStok(Request $request)
    {
        $items  = $request->input('items', []);
        $errors = [];

        if (empty($items) || !is_array($items)) {
            return response()->json(['ok' => true, 'errors' => []]);
        }

        foreach ($items as $item) {
            $barangId = $item['barang_id'] ?? null;
            $qty      = (int) ($item['qty'] ?? 0);
            if (!$barangId || $qty <= 0) continue;

            $barang = Barang::find($barangId);
            if (!$barang) {
                $errors[] = "Barang dengan ID {$barangId} tidak ditemukan.";
                continue;
            }
            if ((int) $barang->stok < $qty) {
                $errors[] = "Stok <strong>{$barang->nama_barang}</strong> tidak cukup "
                          . "(diminta: {$qty}, tersedia: {$barang->stok}).";
            }
        }

        return response()->json(['ok' => empty($errors), 'errors' => $errors]);
    }

    public function simpanInvoice(Request $request)
    {
        // CHANGED: tambah validasi jasa_nama (wajib saat kategori jasa)
        //          dan pajak dibatasi max 100
        $request->validate([
            'tanggal_invoice' => 'required|date',
            'kategori'        => 'required|in:barang,jasa',
            'grand_total'     => 'required|numeric|min:0',
            'nama_pelanggan'  => 'required|string|max:100',
            'kontak'          => ['required', 'string', 'regex:/^08[0-9]{8,13}$/'],
            'jasa_nama'       => 'required_if:kategori,jasa|nullable|string|max:255',
            'pajak'           => 'nullable|numeric|min:0|max:100',
        ], [
            // Pesan error custom agar lebih ramah
            'jasa_nama.required_if' => 'Nama jasa / service wajib diisi.',
            'pajak.max'             => 'Pajak tidak boleh lebih dari 100%.',
            'kontak.regex'          => 'Kontak harus diawali 08 dan terdiri dari 10–15 digit angka.',
        ]);

        DB::beginTransaction();

        try {
            $kategori      = $request->kategori;
            $namaPelanggan = $request->nama_pelanggan ?? null;
            $kontak        = $request->kontak ?? null;
            $deskripsi     = trim($request->deskripsi ?? '');
            $userId        = auth()->id();

            // ── Snapshot user pembuat invoice ─────────────────────────────────
            $currentUser = auth()->user();

            $subtotalBarang = (float) ($request->subtotal_barang ?? 0);
            $biayaJasa      = $kategori === 'jasa' ? (float) ($request->subtotal_jasa ?? 0) : 0;
            $subtotal       = $subtotalBarang + $biayaJasa;

            $diskon = max(0, (float) ($request->diskon ?? 0));
            // CHANGED: clamp pajak 0–100 sebagai safeguard server-side
            $pajak  = min(100, max(0, (int) ($request->pajak ?? 0)));

            $invoice = Invoice::create([
                'user_id'         => $userId,
                'tanggal_invoice' => $request->tanggal_invoice,
                'subtotal_barang' => $subtotalBarang,
                'biaya_jasa'      => $biayaJasa,
                'subtotal'        => $subtotal,
                'status'          => 'Pending',
                'tanggal_bayar'   => null,
            ]);

            if ($kategori === 'barang') {
                $this->simpanItemBarang(
                    $invoice->invoice_id,
                    $request->barang ?? [],
                    'Barang',
                    $namaPelanggan,
                    $kontak
                );
            }

            if ($kategori === 'jasa') {
                $jasaNama = $request->jasa_nama ?? 'Jasa';

                $this->simpanItemJasa(
                    $invoice->invoice_id,
                    $jasaNama,
                    $biayaJasa,
                    $namaPelanggan,
                    $kontak
                );

                $jasaBarang        = $request->jasa_barang ?? [];
                $itemsDenganBarang = array_filter($jasaBarang, fn($i) => !empty($i['barang_id']));

                if (!empty($itemsDenganBarang)) {
                    $this->simpanItemBarang(
                        $invoice->invoice_id,
                        $itemsDenganBarang,
                        'Barang',
                        $namaPelanggan,
                        $kontak
                    );
                }
            }

            DetailInvoice::create([
                'invoice_id'     => $invoice->invoice_id,
                'barang_id'      => null,
                'nama_pelanggan' => $namaPelanggan,
                'kontak'         => $kontak,
                'deskripsi'      => $deskripsi !== '' ? $deskripsi : '-',
                'jumlah'         => '0',
                'harga_satuan'   => 0,
                'total'          => 0,
                'tipe_transaksi' => 'Jasa',
                'diskon'         => $diskon,
                'pajak'          => $pajak,
            ]);

            RiwayatTransaksi::create([
                'invoice_id'                => $invoice->invoice_id,
                'user_id'                   => $userId,
                'tanggal_riwayat_transaksi' => now(),
                // ── Snapshot user pembuat invoice ─────────────────────────────
                'username_snapshot'         => $currentUser->username,
                'email_snapshot'            => $currentUser->email,
            ]);

            DB::commit();

            $role = auth()->user()->role;

            return $role === 'staff'
                ? redirect()->route('riwayat_transaksi_staff')->with('success', 'Invoice berhasil disimpan.')
                : redirect()->route('tampilan_konfirmasi_invoice')->with('success', 'Invoice berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Invoice store error: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // ── Private helpers ──────────────────────────────────────────────────────

    private function simpanItemJasa(
        int     $invoiceId,
        string  $jasaNama,
        float   $biayaJasa,
        ?string $namaPelanggan,
        ?string $kontak
    ): void {
        DetailInvoice::create([
            'invoice_id'     => $invoiceId,
            'barang_id'      => null,
            'nama_pelanggan' => $namaPelanggan,
            'kontak'         => $kontak,
            'deskripsi'      => $jasaNama,
            'jumlah'         => '1',
            'harga_satuan'   => $biayaJasa,
            'total'          => $biayaJasa,
            'tipe_transaksi' => 'Jasa',
            'diskon'         => null,
            'pajak'          => null,
        ]);
    }

    private function simpanItemBarang(
        int     $invoiceId,
        array   $items,
        string  $tipe = 'Barang',
        ?string $namaPelanggan = null,
        ?string $kontak = null
    ): void {
        foreach ($items as $item) {
            if (empty($item['barang_id'])) continue;

            $barang = Barang::findOrFail($item['barang_id']);
            $qty    = (int) ($item['qty'] ?? 0);
            if ($qty <= 0) continue;

            if ((int) $barang->stok < $qty) {
                throw new \Exception(
                    "Stok {$barang->nama_barang} tidak cukup (tersedia: {$barang->stok})."
                );
            }

            $totalItem   = (float) ($item['total'] ?? 0);
            $hargaSatuan = $qty > 0 ? $totalItem / $qty : (float) $barang->harga_jual;

            DetailInvoice::create([
                'invoice_id'     => $invoiceId,
                'barang_id'      => $barang->barang_id,
                'nama_pelanggan' => $namaPelanggan,
                'kontak'         => $kontak,
                'deskripsi'      => $barang->nama_barang,
                'harga_satuan'   => $hargaSatuan,
                'jumlah'         => (string) $qty,
                'total'          => $totalItem,
                'tipe_transaksi' => $tipe,
                'diskon'         => null,
                'pajak'          => null,
            ]);
        }
    }

    /**
     * Dipanggil saat invoice dikonfirmasi Paid.
     * Snapshot user diambil dari user yang mengkonfirmasi (admin yang melakukan aksi ini).
     */
    private function prosesStokDariInvoice(Invoice $invoice): void
    {
        $userId      = $invoice->user_id;
        $tanggalHari = $invoice->tanggal_invoice instanceof \Carbon\Carbon
            ? $invoice->tanggal_invoice->toDateString()
            : $invoice->tanggal_invoice;

        $waktuKeluar = $invoice->tanggal_bayar;

        // ── Snapshot: gunakan user yang terkait di invoice (pembuat invoice) ──
        // Ambil dari riwayat_transaksi agar pakai snapshot yang sudah tersimpan
        $riwayat = $invoice->riwayatTransaksi;
        $usernameSnap = $riwayat?->username_snapshot ?? $invoice->user?->username ?? '-';
        $emailSnap    = $riwayat?->email_snapshot    ?? $invoice->user?->email    ?? '-';

        $items = $invoice->items()
            ->whereNotNull('barang_id')
            ->where('jumlah', '>', '0')
            ->get();

        foreach ($items as $item) {
            $barang = Barang::find($item->barang_id);

            if (!$barang) continue;

            $qty = (int) $item->jumlah;
            if ($qty <= 0) continue;

            if ((int) $barang->stok < $qty) {
                throw new \Exception(
                    "Stok {$barang->nama_barang} tidak cukup saat konfirmasi (tersedia: {$barang->stok})."
                );
            }

            $riwayatHariIniPertama = RiwayatStok::where('barang_id', $barang->barang_id)
                ->whereDate('tanggal_riwayat_stok', $tanggalHari)
                ->orderBy('riwayat_stok_id', 'asc')
                ->first();

            $riwayatHariIniTerakhir = RiwayatStok::where('barang_id', $barang->barang_id)
                ->whereDate('tanggal_riwayat_stok', $tanggalHari)
                ->orderBy('riwayat_stok_id', 'desc')
                ->first();

            $riwayatSebelumnya = RiwayatStok::where('barang_id', $barang->barang_id)
                ->whereDate('tanggal_riwayat_stok', '<', $tanggalHari)
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

            $barang->update(['stok' => (string) $stokAkhir]);

            $barangKeluar = BarangKeluar::create([
                'user_id'              => $userId,
                'barang_id'            => $barang->barang_id,
                'jumlah_keluar'        => $qty,
                'tanggal_keluar'       => $waktuKeluar,
                'keterangan'           => 'Invoice',
                'ref_invoice'          => $invoice->invoice_id,
                'kode_barang_snapshot' => $barang->kode_barang,
                'nama_barang_snapshot' => $barang->nama_barang,
                'satuan_snapshot'      => $barang->satuan,
                // ── Snapshot user pembuat invoice ─────────────────────────────
                'username_snapshot'    => $usernameSnap,
                'email_snapshot'       => $emailSnap,
            ]);

            RiwayatStok::create([
                'barang_id'            => $barang->barang_id,
                'user_id'              => $userId,
                'barang_masuk_id'      => null,
                'barang_keluar_id'     => $barangKeluar->barang_keluar_id,
                'tanggal_riwayat_stok' => $waktuKeluar,
                'stok_awal'            => $stokAwal,
                'stok_akhir'           => $stokAkhir,
                'kode_barang_snapshot' => $barang->kode_barang,
                'nama_barang_snapshot' => $barang->nama_barang,
                // ── Snapshot user ─────────────────────────────────────────────
                'username_snapshot'    => $usernameSnap,
                'email_snapshot'       => $emailSnap,
            ]);
        }
    }

    // ── Admin only ───────────────────────────────────────────────────────────

    public function hapusKonfirmasi(string $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->items()->delete();
        $invoice->delete();

        return redirect()->route('tampilan_konfirmasi_invoice')
            ->with('success', 'Invoice berhasil dihapus.');
    }
}