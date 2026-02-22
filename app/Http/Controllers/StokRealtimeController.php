<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class StokRealtimeController extends Controller
{
    public function getStokRealtime(Request $request)
    {
        $q = $request->input('q');

        $query = Barang::query()->orderBy('barang_id', 'asc');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $like = "%{$q}%";
                $sub->where('kode_barang', 'like', $like)
                    ->orWhere('nama_barang', 'like', $like);
            });
        }

        $barangs = $query->get();

        return view('admin.stok_realtime', compact('barangs', 'q'));
    }

    public function print(Request $request)
    {
        $barangs = Barang::orderBy('barang_id', 'asc')->get();
        return view('admin.print.stokrealtime', compact('barangs'));
    }
}