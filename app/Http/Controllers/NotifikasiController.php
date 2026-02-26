<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    public function getTampilanNotifikasi()
    {
        $notifs = Notifikasi::orderBy('tanggal_dibuat', 'desc')->get();

        return view('admin.notifikasi.tampilan_notifikasi', compact('notifs'));
    }

    public function getDetailNotifikasi($id)
    {
        $notif = Notifikasi::findOrFail($id);

        return view('admin.notifikasi.detail_notifikasi', compact('notif'));
    }
}