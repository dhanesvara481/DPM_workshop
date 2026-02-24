<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RiwayatTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getRiwayatTransaksi()
    {
        return view('admin.riwayat_transaksi.riwayat_transaksi', [
        'riwayatTransaksis' => [],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getDetailRiwayatTransaksi()
    {
        return view('admin.riwayat_transaksi.detail_riwayat_transaksi', [
        'detailRiwayatTransaksis' => [],
        ]);
    }
    
    // public function detail($id)
    // {
    //     $trx = Transaksi::with(['items.barang'])->findOrFail($id); // sesuaikan relasi
    //     $items = $trx->items ?? [];

    //     return view('admin.detail_riwayat_transaksi', [
    //         'trx' => $trx,
    //         'items' => $items,
    //         'userName' => auth()->user()->name ?? 'User',
    //         'role' => auth()->user()->role ?? 'Admin',
    //     ]);
    // }

    // public function nota($id)
    // {
    //     $trx = Transaksi::with(['items.barang'])->findOrFail($id);
    //     $items = $trx->items ?? [];

    //     // Versi 1 (paling gampang): halaman print biasa (window.print)
    //     return view('admin.riwayat_transaksi.print_transaksi', compact('trx', 'items'));
    // }

    // STAFF CONTROLLER
    public function getRiwayatTransaksiStaff()
    {
        return view('staff.riwayat_transaksi.riwayat_transaksi_staff', [
        'riwayatTransaksis' => [],
        ]);
    }

     public function getDetailRiwayatTransaksiStaff()
    {
        return view('staff.riwayat_transaksi.detail_riwayat_transaksi_staff', [
        'detailRiwayatTransaksis' => [],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
