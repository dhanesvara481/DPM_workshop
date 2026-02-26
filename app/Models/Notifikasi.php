<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';
    protected $primaryKey = 'notifikasi_id';

    protected $fillable = [
        'jenis_notifikasi',
        'judul_notif',
        'isi_pesan',
        'tanggal_dibuat',
        'tanggal_dikirim',
    ];

    public $timestamps = true;
}