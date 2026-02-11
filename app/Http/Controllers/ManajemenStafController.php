<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManajemenStafController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getTampilanManajemenStaf()
    {
        //View manajemen_staf
        return view('admin.manajemen_staf.tampilan_manajemen_staf', [
        'manajemenStafs' => [],
    ]);
    }

    // tambah staf
    public function getTambahStaf()
    {
        //View tambah_staf
        return view('admin.manajemen_staf.tambah_staf', [
        'manajemenStafs' => [],
    ]);
    }
    // tambah staf end
    
    // ubah staf
    public function getUbahStaf()
    {
        //View ubah_staf
        return view('admin.manajemen_staf.ubah_staf', [
        'manajemenStafs' => [],
    ]);
    }
    // ubah staf end

    // detail staf
    public function getDetailStaf()
    {
        //View detail_staf
        return view('admin.manajemen_staf.detail_staf', [
        'manajemenStafs' => [],
    ]);
    }

    public function detail($id) {
        $staf = User::findOrFail($id); // atau model Staf
        return view('admin.manajemen_staf.detail_staf', compact('staf'));
    }
    // detail staf end

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
