<?php

namespace App\Http\Controllers;

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
            ->join('user', 'invoice.user_id', '=', 'user.user_id')
            ->selectRaw("
                riwayat_transaksi.riwayat_transaksi_id  AS id,
                riwayat_transaksi.tanggal_riwayat_transaksi AS created_at,
                riwayat_transaksi.invoice_id,
                invoice.subtotal                         AS total,
                invoice.subtotal_barang,
                invoice.biaya_jasa,
                invoice.tanggal_invoice,
                CONCAT('INV-', invoice.invoice_id)       AS kode_transaksi,
                COALESCE(user.username, 'User')   AS nama_pengguna,
                'paid'                                   AS status
            ")
            ->orderByDesc('riwayat_transaksi.tanggal_riwayat_transaksi')
            ->orderByDesc('riwayat_transaksi.created_at');

        // Filter: pencarian teks (nama user atau kode invoice)
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('user.username',  'like', "%{$q}%")
                    ->orWhereRaw("CONCAT('INV-', invoice.invoice_id) LIKE ?", ["%{$q}%"]);
            });
        }

        // Filter: tanggal dari
        if ($dari) {
            $query->where('riwayat_transaksi.tanggal_riwayat_transaksi', '>=', $dari);
        }

        // Filter: tanggal sampai
        if ($sampai) {
            $query->where('riwayat_transaksi.tanggal_riwayat_transaksi', '<=', $sampai);
        }

        $rows = $query->get();

        return view('admin.riwayat_transaksi.riwayat_transaksi', compact(
            'rows',
            'q',
            'dari',
            'sampai',
        ));
    }

    public function getDetailRiwayatTransaksi(int $id)
    {
        // Eager load: invoice → items (detail_invoice) → barang
        $riwayat = RiwayatTransaksi::with(['invoice.items.barang', 'invoice.user'])
            ->findOrFail($id);

        $invoice = $riwayat->invoice;
        $user    = $invoice->user;

        $trx = (object) [
            'id'                => $riwayat->riwayat_transaksi_id,
            'created_at'        => $riwayat->tanggal_riwayat_transaksi,
            'kode_transaksi'    => 'INV-' . $invoice->invoice_id,
            'nama_pengguna'     => $user?->username ?? 'User',
            'total'             => (float) $invoice->subtotal,
            'subtotal_barang'   => (float) $invoice->subtotal_barang,
            'biaya_jasa'        => (float) $invoice->biaya_jasa,
            'status'            => 'paid',           // belum ada di tabel
            'metode_pembayaran' => '-',              // belum ada di tabel
            'catatan'           => '-',              // belum ada di tabel
            'tipe'              => 'masuk',
        ];

        $items = $invoice->items ?? collect();

        return view('admin.riwayat_transaksi.detail_riwayat_transaksi', compact('trx', 'items'));
    }

    public function nota(int $id)
    {
        $riwayat = RiwayatTransaksi::with(['invoice.items.barang', 'invoice.user'])
            ->findOrFail($id);

        $invoice = $riwayat->invoice;
        $user    = $invoice->user;

        $trx = (object) [
            'id'                => $riwayat->riwayat_transaksi_id,
            'created_at'        => $riwayat->tanggal_riwayat_transaksi,
            'kode_transaksi'    => 'INV-' . $invoice->invoice_id,
            'nama_pengguna'     => $user?->username ?? 'User',
            'total'             => (float) $invoice->subtotal,
            'subtotal_barang'   => (float) $invoice->subtotal_barang,
            'biaya_jasa'        => (float) $invoice->biaya_jasa,
            'status'            => 'paid',
            'metode_pembayaran' => '-',
            'catatan'           => '-',
        ];

        $items = $invoice->items ?? collect();

        return view('admin.riwayat_transaksi.print_transaksi', compact('trx', 'items'));
    }
}