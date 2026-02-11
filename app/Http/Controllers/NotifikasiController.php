<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
     public function getTampilanNotifikasi()
    {
        //View barang_masuk
        return view('admin.notifikasi.tampilan_notifikasi', [
        'notifikasis' => [],
    ]);
    }

     public function getDetailNotifikasi($id)
    {
        //View detail notifikasi
        return view('admin.notifikasi.detail_notifikasi', [
        'notifikasi' => null,
    ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
