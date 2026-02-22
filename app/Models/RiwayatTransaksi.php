<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatTransaksi extends Model
{
    protected $table      = 'riwayat_transaksi';
    protected $primaryKey = 'riwayat_transaksi_id';

    public $timestamps = false;

    protected $fillable = [
        'invoice_id',
        'user_id',
        'tanggal_riwayat_transaksi',
    ];

    protected $casts = [
        'tanggal_riwayat_transaksi' => 'datetime',
    ];

    public function getCreatedAtAttribute()
    {
        return $this->tanggal_riwayat_transaksi;
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'invoice_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}