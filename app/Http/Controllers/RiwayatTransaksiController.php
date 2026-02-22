<?php

namespace App\Http\Controllers;

use App\Models\InvoiceItem;
use App\Models\RiwayatTransaksi;
use Illuminate\Http\Request;

class RiwayatTransaksiController extends Controller
{
    public function getRiwayatTransaksi(Request $request)
    {
        $q      = $request->input('q');
        $dari   = $request->input('dari');
        $sampai = $request->input('sampai');

        $query = RiwayatTransaksi::query()
            ->join('invoice', 'riwayat_transaksi.invoice_id', '=', 'invoice.invoice_id')
            ->join('detail_invoice', 'invoice.invoice_id', '=', 'detail_invoice.invoice_id')
            ->selectRaw("
                riwayat_transaksi.riwayat_transaksi_id       AS id,
                riwayat_transaksi.tanggal_riwayat_transaksi  AS created_at,
                riwayat_transaksi.invoice_id,
                invoice.subtotal                             AS total,
                invoice.subtotal_barang,
                invoice.biaya_jasa,
                invoice.tanggal_invoice,
                CONCAT('INV-', invoice.invoice_id)           AS kode_transaksi,
                COALESCE(detail_invoice.nama_pelanggan, 'User') AS nama_pengguna,
                'paid'                                       AS status
            ")
            ->orderByDesc('riwayat_transaksi.tanggal_riwayat_transaksi');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('detail_invoice.nama_pelanggan', 'like', "%{$q}%")
                    ->orWhereRaw("CONCAT('INV-', invoice.invoice_id) LIKE ?", ["%{$q}%"]);
            });
        }

        if ($dari) {
            $query->whereDate('riwayat_transaksi.tanggal_riwayat_transaksi', '>=', $dari);
        }

        if ($sampai) {
            $query->whereDate('riwayat_transaksi.tanggal_riwayat_transaksi', '<=', $sampai);
        }

        $rows = $query->get();

        return view('admin.riwayat_transaksi.riwayat_transaksi', compact(
            'rows', 'q', 'dari', 'sampai'
        ));
    }

    public function getDetailRiwayatTransaksi(int $id)
    {
        $riwayat = RiwayatTransaksi::with(['invoice.items.barang', 'invoice.detailInvoice'])
            ->findOrFail($id);

        $invoice      = $riwayat->invoice;
        // Ambil nama_pelanggan dari detail_invoice (ambil first karena bisa banyak row)
        $detailInvoice = $invoice->detailInvoice->first();

        $trx = (object) [
            'id'                => $riwayat->riwayat_transaksi_id,
            'created_at'        => $riwayat->tanggal_riwayat_transaksi,
            'kode_transaksi'    => 'INV-' . $invoice->invoice_id,
            'nama_pengguna'     => $detailInvoice?->nama_pelanggan ?? 'User',
            'total'             => (float) $invoice->subtotal,
            'subtotal_barang'   => (float) $invoice->subtotal_barang,
            'biaya_jasa'        => (float) $invoice->biaya_jasa,
            'status'            => 'paid',
            'metode_pembayaran' => '-',
            'catatan'           => '-',
            'tipe'              => 'masuk',
        ];

        $items = $invoice->items ?? collect();

        return view('admin.riwayat_transaksi.detail_riwayat_transaksi', compact('trx', 'items'));
    }

    public function nota(int $id)
    {
        $riwayat = RiwayatTransaksi::with(['invoice.items.barang', 'invoice.detailInvoice']) // <-- fix di sini
            ->findOrFail($id);

        $invoice       = $riwayat->invoice;
        $detailInvoice = $invoice->detailInvoice->first();

        $trx = (object) [
            'id'                => $riwayat->riwayat_transaksi_id,
            'created_at'        => $riwayat->tanggal_riwayat_transaksi,
            'kode_transaksi'    => 'INV-' . $invoice->invoice_id,
            'nama_pengguna'     => $detailInvoice?->nama_pelanggan ?? 'User',
            'total'             => (float) $invoice->subtotal,
            'subtotal_barang'   => (float) $invoice->subtotal_barang,
            'biaya_jasa'        => (float) $invoice->biaya_jasa,
            'status'            => 'paid',
            'metode_pembayaran' => '-',
            'catatan'           => '-',
        ];

        $items = $invoice->items ?? collect();

        return view('admin.print.nota', compact('trx', 'items'));
    }
}