<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    public function getTampilanNotifikasi()
    {
        $notifs = Notifikasi::orderBy('tanggal_dibuat', 'desc')->paginate(10);

        $view = auth()->user()->role === 'admin'
            ? 'admin.notifikasi.tampilan_notifikasi'
            : 'staff.notifikasi.tampilan_notifikasi_staff';

        return view($view, compact('notifs'));
    }

    public function getDetailNotifikasi($id)
    {
        $notif = Notifikasi::findOrFail($id);

        $view = auth()->user()->role === 'admin'
            ? 'admin.notifikasi.detail_notifikasi'
            : 'staff.notifikasi.detail_notifikasi_staff';

        return view($view, compact('notif'));
    }
}