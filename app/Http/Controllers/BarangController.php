<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     *tampilan.kelola_barang.
     */
    public function getBarang()
    {
        //View barang
         return view('admin.mengelola_barang.tampilan_barang', [
        'barangs' => [],
    ]);
    }

       /**
     *tampilan.tambah_barang.
     */
    public function getTambahBarang()
    {
        //View barang
         return view('admin.mengelola_barang.tambah_barang', [
        'barangs' => [],
    ]);
    }

    /**
     *tampilan.ubah_barang.
     */
    public function getUbahBarang()
    {
        //View barang
         return view('admin.mengelola_barang.ubah_barang', [
        'barangs' => [],
    ]);
    }

    /**
     * untuk create data.
     */
    public function create()
    {
        //
    }

    /**
     * ngirim data ke database.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * menampilkan detail data.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * ngubah data
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * untuk update data.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * untuk hapus data.
     */
    public function destroy(string $id)
    {
        //
    }
}
