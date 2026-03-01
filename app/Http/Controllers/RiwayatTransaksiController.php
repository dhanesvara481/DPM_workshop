<?php

namespace App\Http\Controllers;

use App\Models\RiwayatTransaksi;
use Illuminate\Http\Request;

class RiwayatTransaksiController extends Controller
{
    // ===================== ADMIN =====================

    public function getRiwayatTransaksi(Request $request)
    {
        $q       = $request->input('q');
        $dari    = $request->input('dari');
        $sampai  = $request->input('sampai');
        $sort    = $request->input('sort', 'asc');
        $perPage = (int) $request->input('per_page', 15);

        $perPage = in_array($perPage, [10, 15, 25, 50]) ? $perPage : 15;
        $sortDir = $sort === 'desc' ? 'desc' : 'asc';

        $query = RiwayatTransaksi::query()
            ->join('invoice', 'riwayat_transaksi.invoice_id', '=', 'invoice.invoice_id')
            ->join('user', 'riwayat_transaksi.user_id', '=', 'user.user_id')
            ->selectRaw("
                riwayat_transaksi.riwayat_transaksi_id                      AS id,
                riwayat_transaksi.tanggal_riwayat_transaksi                 AS created_at,
                riwayat_transaksi.invoice_id,
                invoice.subtotal_barang,
                invoice.biaya_jasa,
                invoice.subtotal,
                invoice.status,
                invoice.tanggal_invoice,
                user.username                                                AS nama_pembuat,
                CONCAT('INV-', invoice.invoice_id)                          AS kode_transaksi,
                (
                    SELECT di.nama_pelanggan
                    FROM detail_invoice di
                    WHERE di.invoice_id = invoice.invoice_id
                      AND NOT (di.barang_id IS NULL AND di.jumlah = '0' AND di.total = 0)
                    ORDER BY di.detail_invoice_id ASC
                    LIMIT 1
                )                                                            AS nama_pengguna,
                (
                    SELECT di2.tipe_transaksi
                    FROM detail_invoice di2
                    WHERE di2.invoice_id = invoice.invoice_id
                      AND NOT (di2.barang_id IS NULL AND di2.jumlah = '0' AND di2.total = 0)
                    ORDER BY di2.detail_invoice_id ASC
                    LIMIT 1
                )                                                            AS kategori_invoice,
                (
                    SELECT ring.diskon
                    FROM detail_invoice ring
                    WHERE ring.invoice_id = invoice.invoice_id
                      AND ring.barang_id IS NULL
                      AND ring.jumlah    = '0'
                      AND ring.total     = 0
                    LIMIT 1
                )                                                            AS diskon,
                (
                    SELECT ring2.pajak
                    FROM detail_invoice ring2
                    WHERE ring2.invoice_id = invoice.invoice_id
                      AND ring2.barang_id IS NULL
                      AND ring2.jumlah    = '0'
                      AND ring2.total     = 0
                    LIMIT 1
                )                                                            AS pajak
            ")
            ->orderBy('riwayat_transaksi.tanggal_riwayat_transaksi', $sortDir);

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->whereRaw("CONCAT('INV-', invoice.invoice_id) LIKE ?", ["%{$q}%"])
                    ->orWhereExists(function ($ex) use ($q) {
                        $ex->selectRaw('1')
                           ->from('detail_invoice')
                           ->whereColumn('detail_invoice.invoice_id', 'invoice.invoice_id')
                           ->where('detail_invoice.nama_pelanggan', 'like', "%{$q}%");
                    });
            });
        }

        if ($dari)   $query->whereDate('riwayat_transaksi.tanggal_riwayat_transaksi', '>=', $dari);
        if ($sampai) $query->whereDate('riwayat_transaksi.tanggal_riwayat_transaksi', '<=', $sampai);

        $rows = $query->paginate($perPage)->withQueryString();

        return view('admin.riwayat_transaksi.riwayat_transaksi', compact(
            'rows', 'q', 'dari', 'sampai', 'sort', 'perPage'
        ));
    }

    public function getDetailRiwayatTransaksi(int $id)
    {
        $riwayat = RiwayatTransaksi::with(['invoice.items', 'user'])
            ->findOrFail($id);

        $invoice = $riwayat->invoice;

        // Row ringkasan: barang_id=null, jumlah='0', total=0
        // Menyimpan diskon, pajak, dan catatan sekaligus
        $rowRingkasan = $invoice->items->first(
            fn($i) => is_null($i->barang_id) && (float) $i->total == 0 && (int) $i->jumlah == 0
        );

        $catatan = ($rowRingkasan?->deskripsi && $rowRingkasan->deskripsi !== '-')
            ? $rowRingkasan->deskripsi
            : '-';

        $diskon   = (float) ($rowRingkasan?->diskon ?? 0);
        $pajakPct = (int)   ($rowRingkasan?->pajak  ?? 0);

        // Item real: semua kecuali row ringkasan
        $itemsReal = $invoice->items->filter(
            fn($i) => !(is_null($i->barang_id) && (float) $i->total == 0 && (int) $i->jumlah == 0)
        )->values();

        $nama   = $itemsReal->first()?->nama_pelanggan ?? $invoice->items->first()?->nama_pelanggan ?? 'User';
        $kontak = $itemsReal->first()?->kontak ?? $invoice->items->first()?->kontak ?? '-';

        $namaPembuat     = $riwayat->user?->username ?? $riwayat->user?->name ?? 'User';
        $hasJasa         = $itemsReal->contains(fn($i) => $i->tipe_transaksi === 'Jasa');
        $kategoriInvoice = $hasJasa ? 'Jasa' : 'Barang';

        $subtotal    = (float) $invoice->subtotal;
        $afterDisc   = max(0, $subtotal - $diskon);
        $pajakNominal = round($afterDisc * ($pajakPct / 100));
        $grandTotal   = $afterDisc + $pajakNominal;

        $trx = (object) [
            'id'               => $riwayat->riwayat_transaksi_id,
            'created_at'       => $riwayat->tanggal_riwayat_transaksi,
            'kode_transaksi'   => 'INV-' . $invoice->invoice_id,
            'nama_pengguna'    => $nama,
            'kontak'           => $kontak,
            'subtotal'         => $subtotal,
            'subtotal_barang'  => (float) $invoice->subtotal_barang,
            'biaya_jasa'       => (float) $invoice->biaya_jasa,
            'diskon'           => $diskon,
            'pajak'            => $pajakPct,
            'pajak_nominal'    => $pajakNominal,
            'grand_total'      => $grandTotal,
            'status'           => $invoice->status ?? 'Pending',
            'catatan'          => $catatan,
            'nama_pembuat'     => $namaPembuat,
            'kategori_invoice' => $kategoriInvoice,
        ];

        $items = $itemsReal;

        return view('admin.riwayat_transaksi.detail_riwayat_transaksi', compact('trx', 'items'));
    }

    public function nota(int $id)
    {
        $riwayat = RiwayatTransaksi::with(['invoice.items', 'user'])
            ->findOrFail($id);

        $invoice = $riwayat->invoice;

        $rowRingkasan = $invoice->items->first(
            fn($i) => is_null($i->barang_id) && (float) $i->total == 0 && (int) $i->jumlah == 0
        );

        $catatan = ($rowRingkasan?->deskripsi && $rowRingkasan->deskripsi !== '-')
            ? $rowRingkasan->deskripsi
            : '-';

        $diskon   = (float) ($rowRingkasan?->diskon ?? 0);
        $pajakPct = (int)   ($rowRingkasan?->pajak  ?? 0);

        $itemsReal = $invoice->items->filter(
            fn($i) => !(is_null($i->barang_id) && (float) $i->total == 0 && (int) $i->jumlah == 0)
        )->values();

        $nama   = $itemsReal->first()?->nama_pelanggan ?? $invoice->items->first()?->nama_pelanggan ?? 'User';
        $kontak = $itemsReal->first()?->kontak ?? $invoice->items->first()?->kontak ?? '-';

        $namaPembuat = $riwayat->user?->username ?? $riwayat->user?->name ?? 'User';

        $subtotal     = (float) $invoice->subtotal;
        $afterDisc    = max(0, $subtotal - $diskon);
        $pajakNominal = round($afterDisc * ($pajakPct / 100));
        $grandTotal   = $afterDisc + $pajakNominal;

        $trx = (object) [
            'id'              => $riwayat->riwayat_transaksi_id,
            'created_at'      => $riwayat->tanggal_riwayat_transaksi,
            'kode_transaksi'  => 'INV-' . $invoice->invoice_id,
            'nama_pengguna'   => $nama,
            'kontak'          => $kontak,
            'subtotal'        => $subtotal,
            'subtotal_barang' => (float) $invoice->subtotal_barang,
            'biaya_jasa'      => (float) $invoice->biaya_jasa,
            'diskon'          => $diskon,
            'pajak'           => $pajakPct,
            'pajak_nominal'   => $pajakNominal,
            'grand_total'     => $grandTotal,
            'status'          => $invoice->status ?? 'Pending',
            'catatan'         => $catatan,
            'nama_pembuat'    => $namaPembuat,
        ];

        $items = $itemsReal;

        return view('admin.riwayat_transaksi.print_transaksi', compact('trx', 'items'));
    }

    // ===================== STAFF =====================

    public function getRiwayatTransaksiStaff(Request $request)
    {
        $q       = $request->input('q');
        $dari    = $request->input('dari');
        $sampai  = $request->input('sampai');
        $sort    = $request->input('sort', 'desc');
        $perPage = (int) $request->input('per_page', 15);

        $perPage = in_array($perPage, [10, 15, 25, 50]) ? $perPage : 15;
        $sortDir = $sort === 'asc' ? 'asc' : 'desc';

        $query = RiwayatTransaksi::query()
            ->join('invoice', 'riwayat_transaksi.invoice_id', '=', 'invoice.invoice_id')
            ->join('user', 'riwayat_transaksi.user_id', '=', 'user.user_id')
            ->selectRaw("
                riwayat_transaksi.riwayat_transaksi_id                      AS id,
                riwayat_transaksi.tanggal_riwayat_transaksi                 AS created_at,
                riwayat_transaksi.invoice_id,
                invoice.subtotal_barang,
                invoice.biaya_jasa,
                invoice.subtotal,
                invoice.status,
                invoice.tanggal_invoice,
                user.username                                                AS nama_pembuat,
                CONCAT('INV-', invoice.invoice_id)                          AS kode_transaksi,
                (
                    SELECT di.nama_pelanggan
                    FROM detail_invoice di
                    WHERE di.invoice_id = invoice.invoice_id
                      AND NOT (di.barang_id IS NULL AND di.jumlah = '0' AND di.total = 0)
                    ORDER BY di.detail_invoice_id ASC
                    LIMIT 1
                )                                                            AS nama_pengguna,
                (
                    SELECT di2.tipe_transaksi
                    FROM detail_invoice di2
                    WHERE di2.invoice_id = invoice.invoice_id
                      AND NOT (di2.barang_id IS NULL AND di2.jumlah = '0' AND di2.total = 0)
                    ORDER BY di2.detail_invoice_id ASC
                    LIMIT 1
                )                                                            AS kategori_invoice,
                (
                    SELECT ring.diskon
                    FROM detail_invoice ring
                    WHERE ring.invoice_id = invoice.invoice_id
                      AND ring.barang_id IS NULL
                      AND ring.jumlah    = '0'
                      AND ring.total     = 0
                    LIMIT 1
                )                                                            AS diskon,
                (
                    SELECT ring2.pajak
                    FROM detail_invoice ring2
                    WHERE ring2.invoice_id = invoice.invoice_id
                      AND ring2.barang_id IS NULL
                      AND ring2.jumlah    = '0'
                      AND ring2.total     = 0
                    LIMIT 1
                )                                                            AS pajak
            ")
            ->orderBy('riwayat_transaksi.tanggal_riwayat_transaksi', $sortDir)
            ->where('riwayat_transaksi.user_id', auth()->user()->user_id);

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->whereRaw("CONCAT('INV-', invoice.invoice_id) LIKE ?", ["%{$q}%"])
                    ->orWhereExists(function ($ex) use ($q) {
                        $ex->selectRaw('1')
                           ->from('detail_invoice')
                           ->whereColumn('detail_invoice.invoice_id', 'invoice.invoice_id')
                           ->where('detail_invoice.nama_pelanggan', 'like', "%{$q}%");
                    });
            });
        }

        if ($dari)   $query->whereDate('riwayat_transaksi.tanggal_riwayat_transaksi', '>=', $dari);
        if ($sampai) $query->whereDate('riwayat_transaksi.tanggal_riwayat_transaksi', '<=', $sampai);

        $rows = $query->paginate($perPage)->withQueryString();

        return view('staff.riwayat_transaksi.riwayat_transaksi_staff', compact(
            'rows', 'q', 'dari', 'sampai', 'sort', 'perPage'
        ));
    }

    public function getDetailRiwayatTransaksiStaff(int $id)
    {
        $riwayat = RiwayatTransaksi::with(['invoice.items', 'user'])
            ->where('user_id', auth()->user()->user_id)
            ->findOrFail($id);

        $invoice = $riwayat->invoice;

        $rowRingkasan = $invoice->items->first(
            fn($i) => is_null($i->barang_id) && (float) $i->total == 0 && (int) $i->jumlah == 0
        );

        $catatan = ($rowRingkasan?->deskripsi && $rowRingkasan->deskripsi !== '-')
            ? $rowRingkasan->deskripsi
            : '-';

        $diskon   = (float) ($rowRingkasan?->diskon ?? 0);
        $pajakPct = (int)   ($rowRingkasan?->pajak  ?? 0);

        $itemsReal = $invoice->items->filter(
            fn($i) => !(is_null($i->barang_id) && (float) $i->total == 0 && (int) $i->jumlah == 0)
        )->values();

        $nama   = $itemsReal->first()?->nama_pelanggan ?? $invoice->items->first()?->nama_pelanggan ?? 'User';
        $kontak = $itemsReal->first()?->kontak ?? $invoice->items->first()?->kontak ?? '-';

        $namaPembuat     = $riwayat->user?->username ?? $riwayat->user?->name ?? 'User';
        $hasJasa         = $itemsReal->contains(fn($i) => $i->tipe_transaksi === 'Jasa');
        $kategoriInvoice = $hasJasa ? 'Jasa' : 'Barang';

        $subtotal     = (float) $invoice->subtotal;
        $afterDisc    = max(0, $subtotal - $diskon);
        $pajakNominal = round($afterDisc * ($pajakPct / 100));
        $grandTotal   = $afterDisc + $pajakNominal;

        $trx = (object) [
            'id'               => $riwayat->riwayat_transaksi_id,
            'created_at'       => $riwayat->tanggal_riwayat_transaksi,
            'kode_transaksi'   => 'INV-' . $invoice->invoice_id,
            'nama_pengguna'    => $nama,
            'kontak'           => $kontak,
            'subtotal'         => $subtotal,
            'subtotal_barang'  => (float) $invoice->subtotal_barang,
            'biaya_jasa'       => (float) $invoice->biaya_jasa,
            'diskon'           => $diskon,
            'pajak'            => $pajakPct,
            'pajak_nominal'    => $pajakNominal,
            'grand_total'      => $grandTotal,
            'status'           => $invoice->status ?? 'Pending',
            'catatan'          => $catatan,
            'nama_pembuat'     => $namaPembuat,
            'kategori_invoice' => $kategoriInvoice,
        ];

        $items = $itemsReal;

        return view('staff.riwayat_transaksi.detail_riwayat_transaksi_staff', compact('trx', 'items'));
    }

    public function notaStaff(int $id)
    {
        $riwayat = RiwayatTransaksi::with(['invoice.items', 'user'])
            ->where('user_id', auth()->user()->user_id)
            ->findOrFail($id);

        $invoice = $riwayat->invoice;

        $rowRingkasan = $invoice->items->first(
            fn($i) => is_null($i->barang_id) && (float) $i->total == 0 && (int) $i->jumlah == 0
        );

        $catatan = ($rowRingkasan?->deskripsi && $rowRingkasan->deskripsi !== '-')
            ? $rowRingkasan->deskripsi
            : '-';

        $diskon   = (float) ($rowRingkasan?->diskon ?? 0);
        $pajakPct = (int)   ($rowRingkasan?->pajak  ?? 0);

        $itemsReal = $invoice->items->filter(
            fn($i) => !(is_null($i->barang_id) && (float) $i->total == 0 && (int) $i->jumlah == 0)
        )->values();

        $nama   = $itemsReal->first()?->nama_pelanggan ?? $invoice->items->first()?->nama_pelanggan ?? 'User';
        $kontak = $itemsReal->first()?->kontak ?? $invoice->items->first()?->kontak ?? '-';

        $namaPembuat = $riwayat->user?->username ?? $riwayat->user?->name ?? 'User';

        $subtotal     = (float) $invoice->subtotal;
        $afterDisc    = max(0, $subtotal - $diskon);
        $pajakNominal = round($afterDisc * ($pajakPct / 100));
        $grandTotal   = $afterDisc + $pajakNominal;

        $trx = (object) [
            'id'              => $riwayat->riwayat_transaksi_id,
            'created_at'      => $riwayat->tanggal_riwayat_transaksi,
            'kode_transaksi'  => 'INV-' . $invoice->invoice_id,
            'nama_pengguna'   => $nama,
            'kontak'          => $kontak,
            'subtotal'        => $subtotal,
            'subtotal_barang' => (float) $invoice->subtotal_barang,
            'biaya_jasa'      => (float) $invoice->biaya_jasa,
            'diskon'          => $diskon,
            'pajak'           => $pajakPct,
            'pajak_nominal'   => $pajakNominal,
            'grand_total'     => $grandTotal,
            'status'          => $invoice->status ?? 'Pending',
            'catatan'         => $catatan,
            'nama_pembuat'    => $namaPembuat,
        ];

        $items = $itemsReal;

        return view('staff.riwayat_transaksi.print_transaksi_staff', compact('trx', 'items'));
    }
}