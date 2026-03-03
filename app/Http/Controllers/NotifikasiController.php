<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    public function getTampilanNotifikasi()
    {
        $notifs = Notifikasi::orderBy('tanggal_dibuat', 'desc')->paginate(10);

        return view('admin.notifikasi.tampilan_notifikasi', compact('notifs'));
    }

    public function getDetailNotifikasi($id)
    {
        $notif = Notifikasi::findOrFail($id);

        return view('admin.notifikasi.detail_notifikasi', compact('notif'));
    }

    public function getNotifikasiStaff()
    {
        $notifs = Notifikasi::orderBy('tanggal_dibuat', 'desc')->paginate(10);

        return view('staff.notifikasi.tampilan_notifikasi_staff', compact('notifs'));
    }

    public function getDetailNotifikasiStaff($id)
    {
        $notif = Notifikasi::findOrFail($id);

        return view('staff.notifikasi.detail_notifikasi_staff', compact('notif'));
    }
}