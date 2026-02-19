<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $table      = 'barang_keluar';
    protected $primaryKey = 'barang_keluar_id';

    protected $fillable = [
        'user_id',
        'barang_id',
        'jumlah_keluar',
        'tanggal_keluar',
        'keterangan',
        'ref_invoice',
    ];

    protected $casts = [
        'tanggal_keluar' => 'date',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function scopeSearch($query, ?string $keyword)
    {
        if (!$keyword) return $query;

        return $query->whereHas('barang', function ($q) use ($keyword) {
            $q->where('kode_barang', 'like', "%{$keyword}%")
              ->orWhere('nama_barang', 'like', "%{$keyword}%");
        });
    }
}