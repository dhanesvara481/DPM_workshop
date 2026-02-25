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

class InvoiceController extends Controller
{
    // ── Admin ────────────────────────────────────────────────────────────────

    public function getTampilanInvoice()
    {
        // Tabel: barang | kolom stok: VARCHAR(10) → CAST untuk filter > 0
        $barangs = Barang::whereRaw('CAST(stok AS UNSIGNED) > 0')
            ->orderBy('barang_id')
            ->get(['barang_id', 'kode_barang', 'nama_barang', 'satuan', 'harga_jual', 'stok']);

        return view('admin.invoice.tampilan_invoice', compact('barangs'));
    }

    public function checkStok(Request $request)
    {
        $items  = $request->input('items', []);
        $errors = [];

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

    // ── Staff ────────────────────────────────────────────────────────────────

    public function getTampilanInvoiceStaff()
    {
        // Sama persis dengan admin — staff juga butuh daftar barang untuk dropdown
        $barangs = Barang::whereRaw('CAST(stok AS UNSIGNED) > 0')
            ->orderBy('barang_id')
            ->get(['barang_id', 'kode_barang', 'nama_barang', 'satuan', 'harga_jual', 'stok']);

        return view('staff.invoice.tampilan_invoice_staff', compact('barangs'));
    }

    // ── Store (dipakai admin & staff, redirect sesuai role) ──────────────────

    public function store(Request $request)
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
            $userId        = auth()->user()->user_id;

            $invoice = Invoice::create([
                'user_id'         => $userId,
                'tanggal_invoice' => $request->tanggal_invoice,
                'subtotal_barang' => $request->subtotal_barang ?? 0,
                'biaya_jasa'      => $kategori === 'jasa' ? ($request->subtotal_jasa ?? 0) : 0,
                'subtotal'        => $request->grand_total ?? 0,
            ]);

            if ($kategori === 'barang') {
                $this->simpanItemBarang(
                    $invoice->invoice_id,
                    $request->barang ?? [],
                    'Barang',
                    $namaPelanggan,
                    $kontak,
                    $userId,
                    $request->tanggal_invoice
                );
            }

            if ($kategori === 'jasa') {
                $jasaBarang        = $request->jasa_barang ?? [];
                $itemsDenganBarang = array_filter($jasaBarang, fn($i) => !empty($i['barang_id']));

                if (!empty($itemsDenganBarang)) {
                    $this->simpanItemBarang(
                        $invoice->invoice_id,
                        $itemsDenganBarang,
                        'Jasa',
                        $namaPelanggan,
                        $kontak,
                        $userId,
                        $request->tanggal_invoice
                    );
                }
            }

            RiwayatTransaksi::create([
                'invoice_id'                => $invoice->invoice_id,
                'user_id'                   => $userId,
                'tanggal_riwayat_transaksi' => now(),
            ]);

            DB::commit();

            // Redirect ke halaman invoice sesuai role
            $role = auth()->user()->role;

            if ($role === 'staff') {
                return redirect()->route('tampilan_invoice_staff')
                    ->with('success', 'Invoice berhasil disimpan.');
            }

            return redirect()->route('tampilan_invoice')
                ->with('success', 'Invoice berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // ── Private helpers ──────────────────────────────────────────────────────

    private function simpanItemBarang(
        int     $invoiceId,
        array   $items,
        string  $tipe = 'Barang',
        ?string $namaPelanggan = null,
        ?string $kontak = null,
        int     $userId = 0,
        ?string $tanggal = null
    ): void {
        $tanggalHari = $tanggal ?? now()->toDateString();

        foreach ($items as $item) {
            if (empty($item['barang_id'])) continue;

            $barang = Barang::findOrFail($item['barang_id']);
            $qty    = (int) ($item['qty'] ?? 0);
            if ($qty <= 0) continue;

            // Stok VARCHAR — cast untuk perbandingan
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
            ]);

            // Riwayat stok: gunakan stok_awal dari record pertama hari ini kalau sudah ada
            $riwayatHariIni = RiwayatStok::where('barang_id', $barang->barang_id)
                ->whereDate('tanggal_riwayat_stok', $tanggalHari)
                ->orderBy('riwayat_stok_id', 'asc')
                ->first();

            $stokAwal = $riwayatHariIni ? (int) $riwayatHariIni->stok_awal : (int) $barang->stok;

            $barang->decrement('stok', $qty);
            $stokAkhir = (int) $barang->fresh()->stok;

            $barangKeluar = BarangKeluar::create([
                'user_id'        => $userId,
                'barang_id'      => $barang->barang_id,
                'jumlah_keluar'  => $qty,
                'tanggal_keluar' => $tanggalHari,
                'ref_invoice'    => $invoiceId,
            ]);

            RiwayatStok::create([
                'barang_id'            => $barang->barang_id,
                'user_id'              => $userId,
                'barang_masuk_id'      => null,
                'barang_keluar_id'     => $barangKeluar->barang_keluar_id,
                'tanggal_riwayat_stok' => $tanggalHari,
                'stok_awal'            => $stokAwal,
                'stok_akhir'           => $stokAkhir,
            ]);
        }
    }

    // ── Admin only ───────────────────────────────────────────────────────────

    public function show(string $id)
    {
        $invoice = Invoice::with(['items.barang', 'user'])->findOrFail($id);
        return view('admin.invoice.detail', compact('invoice'));
    }

    public function destroy(string $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->items()->delete();
        $invoice->delete();

        return redirect()->route('tampilan_invoice')
            ->with('success', 'Invoice berhasil dihapus.');
    }
}