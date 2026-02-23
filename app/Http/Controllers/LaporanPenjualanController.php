<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiwayatTransaksi;
use Carbon\Carbon;

class LaporanPenjualanController extends Controller
{
    private function buildQuery(Request $request)
    {
        $mode   = $request->input('mode', 'custom');
        $dari   = $request->input('dari');
        $sampai = $request->input('sampai');
        $week   = $request->input('week');
        $month  = $request->input('month');
        $year   = $request->input('year');

        $query = RiwayatTransaksi::query()
            ->join('invoice',        'riwayat_transaksi.invoice_id', '=', 'invoice.invoice_id')
            ->leftJoin('detail_invoice', 'invoice.invoice_id',       '=', 'detail_invoice.invoice_id')
            ->selectRaw("
                riwayat_transaksi.riwayat_transaksi_id                  AS id,
                riwayat_transaksi.tanggal_riwayat_transaksi             AS created_at,
                CONCAT('INV-', invoice.invoice_id)                      AS kode_transaksi,
                invoice.subtotal                                         AS total,
                COALESCE(detail_invoice.nama_pelanggan, 'Umum')         AS nama_pengguna
            ")
            ->orderByDesc('riwayat_transaksi.tanggal_riwayat_transaksi');

        if ($mode === 'custom' && $dari && $sampai) {
            $query->whereDate('riwayat_transaksi.tanggal_riwayat_transaksi', '>=', $dari)
                  ->whereDate('riwayat_transaksi.tanggal_riwayat_transaksi', '<=', $sampai);

        } elseif ($mode === 'week' && $week) {
            [$yr, $wk] = explode('-W', $week);
            $start = Carbon::now()->setISODate((int)$yr, (int)$wk)->startOfWeek()->toDateString();
            $end   = Carbon::now()->setISODate((int)$yr, (int)$wk)->endOfWeek()->toDateString();
            $query->whereDate('riwayat_transaksi.tanggal_riwayat_transaksi', '>=', $start)
                  ->whereDate('riwayat_transaksi.tanggal_riwayat_transaksi', '<=', $end);

        } elseif ($mode === 'month' && $month) {
            $start = Carbon::parse($month . '-01')->startOfMonth()->toDateString();
            $end   = Carbon::parse($month . '-01')->endOfMonth()->toDateString();
            $query->whereDate('riwayat_transaksi.tanggal_riwayat_transaksi', '>=', $start)
                  ->whereDate('riwayat_transaksi.tanggal_riwayat_transaksi', '<=', $end);

        } elseif ($mode === 'year' && $year) {
            $query->whereYear('riwayat_transaksi.tanggal_riwayat_transaksi', $year);
        }

        return $query;
    }

    private function filterParams(Request $request): array
    {
        return [
            'mode'   => $request->input('mode', 'custom'),
            'dari'   => $request->input('dari'),
            'sampai' => $request->input('sampai'),
            'week'   => $request->input('week'),
            'month'  => $request->input('month'),
            'year'   => $request->input('year'),
        ];
    }


    public function getLaporanPenjualan(Request $request)
    {
        $rows = $this->buildQuery($request)->get();

        extract($this->filterParams($request));

        return view('admin.laporan_penjualan', compact(
            'rows', 'mode', 'dari', 'sampai', 'week', 'month', 'year'
        ));
    }

    public function print(Request $request)
    {
        $rows = $this->buildQuery($request)->get();

        extract($this->filterParams($request));

        return view('admin.print.laporan_keuangan', compact(
            'rows', 'mode', 'dari', 'sampai', 'week', 'month', 'year'
        ));
    }
}