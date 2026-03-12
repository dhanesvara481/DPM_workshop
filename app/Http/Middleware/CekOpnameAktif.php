<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CekOpnameAktif
{
    /**
     * Blokir transaksi stok (barang masuk, barang keluar, konfirmasi invoice)
     * jika ada sesi stok opname yang sedang aktif (status: draft atau menunggu_approval).
     *
     * Tujuan: mencegah perubahan stok di tengah proses opname yang bisa
     * menyebabkan selisih tidak akurat antara stok sistem vs stok fisik.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $opnameAktif = DB::table('stok_opname')
            ->whereIn('status', ['draft', 'menunggu_approval'])
            ->first();

        if ($opnameAktif) {
            // Format tanggal opname untuk pesan error
            $tanggal = \Carbon\Carbon::parse($opnameAktif->tanggal_opname)->format('d M Y');
            $status  = $opnameAktif->status === 'draft' ? 'Draft' : 'Menunggu Persetujuan';

            $pesan = "Pergerakan Stok dinonaktifkan sementara karena ada sesi Stok Opname aktif "
                   . "(Tanggal: {$tanggal}, Status: {$status}). "
                   . "Selesaikan atau batalkan sesi opname terlebih dahulu sebelum melakukan transaksi stok.";

            // Jika request AJAX / JSON → kembalikan response JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'ok'      => false,
                    'locked'  => true,
                    'message' => $pesan,
                ], 423); // 423 Locked
            }

            // Request biasa → redirect back dengan pesan error
            return redirect()->back()->with('error', $pesan);
        }

        return $next($request);
    }
}