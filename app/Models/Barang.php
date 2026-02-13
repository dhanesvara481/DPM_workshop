<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    
    protected $primaryKey = 'barang_id';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'stok',
        'satuan',
        'harga_beli',
        'harga_jual',
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
    ];
}