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
        $barangs = Barang::whereRaw('CAST(stok AS UNSIGNED) > 0')
            ->orderBy('barang_id')
            ->get(['barang_id', 'kode_barang', 'nama_barang', 'satuan', 'harga_jual', 'stok']);

        return view('staff.invoice.tampilan_invoice_staff', compact('barangs'));
    }

    // ── Store (dipakai admin & staff) ────────────────────────────────────────

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

    /**
     * Simpan item barang ke invoice + catat riwayat stok.
     *
     * Aturan stok_awal & stok_akhir:
     *
     *   Hari 1  masuk    qty 100  => stok_awal 100, stok_akhir 100
     *   Hari 1  masuk    qty  50  => stok_awal 100, stok_akhir 150
     *   Hari 1  keluar   qty  25  => stok_awal 100, stok_akhir 125
     *   Hari 2  masuk    qty  55  => stok_awal 125, stok_akhir 180
     *   Hari 2  invoice  qty  20  => stok_awal 125, stok_akhir 160
     *
     * - stok_awal  = stok_awal record PERTAMA hari ini (tetap sepanjang hari)
     *               Jika belum ada transaksi hari ini:
     *                 ada riwayat sebelumnya       -> stok_awal = stok_akhir terakhir kemarin
     *                 tidak ada riwayat sama sekali -> stok_awal = stok barang saat ini
     *
     * - stok_akhir = stok_akhir record TERAKHIR hari ini - qty
     *
     * PENTING: update stok barang pakai nilai stok_akhir yang sudah dihitung,
     *          BUKAN decrement(), agar tidak terjadi double-kurang.
     */
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

            // ── Tentukan stok_awal & stok_akhir ────────────────────────────

            // Record pertama hari ini → stok_awal awal hari (tidak berubah sepanjang hari)
            $riwayatHariIniPertama = RiwayatStok::where('barang_id', $barang->barang_id)
                ->whereDate('tanggal_riwayat_stok', $tanggalHari)
                ->orderBy('riwayat_stok_id', 'asc')
                ->first();

            // Record terakhir hari ini → titik lanjut stok_akhir
            $riwayatHariIniTerakhir = RiwayatStok::where('barang_id', $barang->barang_id)
                ->whereDate('tanggal_riwayat_stok', $tanggalHari)
                ->orderBy('riwayat_stok_id', 'desc')
                ->first();

            // Record terakhir sebelum hari ini → menjadi stok_awal hari baru
            $riwayatSebelumnya = RiwayatStok::where('barang_id', $barang->barang_id)
                ->whereDate('tanggal_riwayat_stok', '<', $tanggalHari)
                ->orderBy('tanggal_riwayat_stok', 'desc')
                ->orderBy('riwayat_stok_id', 'desc')
                ->first();

            if ($riwayatHariIniPertama) {
                // Sudah ada transaksi hari ini
                $stokAwal  = (int) $riwayatHariIniPertama->stok_awal;
                $stokAkhir = (int) $riwayatHariIniTerakhir->stok_akhir - $qty;

            } elseif ($riwayatSebelumnya) {
                // Hari baru, ada riwayat kemarin
                $stokAwal  = (int) $riwayatSebelumnya->stok_akhir;
                $stokAkhir = $stokAwal - $qty;

            } else {
                // Belum ada riwayat sama sekali
                $stokAwal  = (int) $barang->stok;
                $stokAkhir = $stokAwal - $qty;
            }

            // ── Update stok barang dengan nilai yang sudah dihitung ─────────
            // Gunakan update() langsung, BUKAN decrement(), agar konsisten
            // dengan stok_akhir yang tercatat di riwayat_stok
            $barang->update(['stok' => (string) $stokAkhir]);

            // ── Simpan barang_keluar & riwayat_stok ─────────────────────────
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