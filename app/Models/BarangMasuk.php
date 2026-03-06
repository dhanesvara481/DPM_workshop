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
        // ── snapshot barang ──
        'kode_barang_snapshot',
        'nama_barang_snapshot',
        'satuan_snapshot',
        // ── snapshot user (admin/staff yang input) ──
        'username_snapshot',
        'email_snapshot',
    ];

    protected $casts = [
        'tanggal_masuk' => 'datetime',
    ];

    // ── Accessor: nama pengguna yang bertanggung jawab ────────────────────────
    // Prioritas: snapshot (tidak berubah walau user edit profil) → relasi live
    public function getNamaPenggunaAttribute(): string
    {
        return $this->username_snapshot ?? $this->user?->username ?? '-';
    }

    public function getEmailPenggunaAttribute(): string
    {
        return $this->email_snapshot ?? $this->user?->email ?? '-';
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}