<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\InvoiceItem;
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

        $query = Invoice::with(['items' => fn($q) => $q->limit(1)])
            ->orderByDesc('tanggal_invoice');

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

        return view('admin.invoice.konfirmasi_invoice', compact('invoices', 'q', 'pendingCount'));
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

    public function getTampilanKonfirmasiStaff(Request $request)
    {
        $q    = $request->input('q', '');
        $from = $request->input('from');
        $to   = $request->input('to');

        $query = Invoice::with(['items' => fn($q) => $q->limit(1)])
            ->orderByDesc('tanggal_invoice');

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

        return view('staff.invoice.konfirmasi_invoice_staff', compact('invoices', 'q', 'pendingCount'));
    }

    public function tandaKonfirmasiStaff(Request $request, $invoice)
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

    // ── Shared (admin & staff) ────────────────────────────────────────────────

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
        $request->validate([
            'tanggal_invoice' => 'required|date',
            'kategori'        => 'required|in:barang,jasa',
            'grand_total'     => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $kategori      = $request->kategori;
            $namaPelanggan = $request->nama_pelanggan ?? null;
            $kontak        = $request->kontak ?? null;
            $deskripsi     = trim($request->deskripsi ?? '');
            $userId        = auth()->id();

            // Nilai keuangan dari form
            $subtotalBarang = (float) ($request->subtotal_barang ?? 0);
            $biayaJasa      = $kategori === 'jasa' ? (float) ($request->subtotal_jasa ?? 0) : 0;
            $subtotal       = $subtotalBarang + $biayaJasa;

            // Diskon & pajak dari form — akan disimpan di row ringkasan detail_invoice
            $diskon    = max(0, (float) ($request->diskon ?? 0));
            $pajak     = max(0, (int)   ($request->pajak  ?? 0)); // dalam persen (%)

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

            // ── Row ringkasan ────────────────────────────────────────────────
            // Satu row khusus di detail_invoice untuk menyimpan diskon, pajak,
            // dan/atau catatan. Selalu dibuat agar grand_total selalu bisa dihitung.
            // Diidentifikasi via: barang_id=null, jumlah='0', total=0.
            InvoiceItem::create([
                'invoice_id'     => $invoice->invoice_id,
                'barang_id'      => null,
                'nama_pelanggan' => $namaPelanggan,
                'kontak'         => $kontak,
                'deskripsi'      => $deskripsi !== '' ? $deskripsi : '-', // '-' jika tidak ada catatan
                'jumlah'         => '0',
                'total'          => 0,
                'tipe_transaksi' => 'Jasa', // enum hanya Barang/Jasa
                'diskon'         => $diskon > 0 ? $diskon : null,
                'pajak'          => $pajak  > 0 ? $pajak  : null,
            ]);

            RiwayatTransaksi::create([
                'invoice_id'                => $invoice->invoice_id,
                'user_id'                   => $userId,
                'tanggal_riwayat_transaksi' => now(),
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
        InvoiceItem::create([
            'invoice_id'     => $invoiceId,
            'barang_id'      => null,
            'nama_pelanggan' => $namaPelanggan,
            'kontak'         => $kontak,
            'deskripsi'      => $jasaNama,
            'jumlah'         => '1', // jumlah > 0 → jasa asli, bukan row ringkasan
            'total'          => $biayaJasa,
            'tipe_transaksi' => 'Jasa',
            'diskon'         => null,
            'pajak'          => null,
        ]);
    }

    /**
     * Simpan item barang ke detail_invoice.
     * Stok belum dikurangi — baru dikurangi saat Paid.
     */
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

            InvoiceItem::create([
                'invoice_id'     => $invoiceId,
                'barang_id'      => $barang->barang_id,
                'nama_pelanggan' => $namaPelanggan,
                'kontak'         => $kontak,
                'deskripsi'      => $barang->nama_barang,
                'jumlah'         => (string) $qty,
                'total'          => (float) ($item['total'] ?? 0),
                'tipe_transaksi' => $tipe,
                'diskon'         => null,
                'pajak'          => null,
            ]);
        }
    }

    /**
     * Dipanggil saat invoice dikonfirmasi Paid.
     * Hanya item dengan barang_id yang mempengaruhi stok.
     * Row ringkasan (barang_id=null, jumlah=0) dilewati otomatis.
     */
    private function prosesStokDariInvoice(Invoice $invoice): void
    {
        $userId      = $invoice->user_id;
        $tanggalHari = $invoice->tanggal_invoice instanceof \Carbon\Carbon
            ? $invoice->tanggal_invoice->toDateString()
            : $invoice->tanggal_invoice;

        $waktuKeluar = $invoice->tanggal_bayar;

        $items = $invoice->items()->whereNotNull('barang_id')->get();

        foreach ($items as $item) {
            $barang = Barang::findOrFail($item->barang_id);
            $qty    = (int) $item->jumlah;

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
                'user_id'        => $userId,
                'barang_id'      => $barang->barang_id,
                'jumlah_keluar'  => $qty,
                'tanggal_keluar' => $waktuKeluar,
                'keterangan'     => 'Invoice',
                'ref_invoice'    => $invoice->invoice_id,
            ]);

            RiwayatStok::create([
                'barang_id'            => $barang->barang_id,
                'user_id'              => $userId,
                'barang_masuk_id'      => null,
                'barang_keluar_id'     => $barangKeluar->barang_keluar_id,
                'tanggal_riwayat_stok' => $waktuKeluar,
                'stok_awal'            => $stokAwal,
                'stok_akhir'           => $stokAkhir,
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