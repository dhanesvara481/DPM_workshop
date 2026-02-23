<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalKerja extends Model
{
    protected $table = 'jadwal_kerja';
    protected $primaryKey = 'jadwal_id';

    protected $fillable = [
        'user_id',
        'tanggal_kerja',
        'waktu_shift',
        'jam_mulai',
        'jam_selesai',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'tanggal_kerja' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'user_id');
    }
}