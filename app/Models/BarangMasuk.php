<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';
    
    protected $primaryKey = 'barang_masuk_id';

    protected $fillable = [
        'barang_id',
        'user_id',
        'jumlah_masuk',
        'tanggal_masuk',
    ];

    protected $casts = [
        'tanggal_masuk' => 'datetime',
    ];

    public function barang()
    {
        return $this->belongsTo (Barang::class, 'barang_id', 'barang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}