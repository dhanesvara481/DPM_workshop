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
        // ── snapshot user (admin/staff yang membuat invoice) ──
        'username_snapshot',
        'email_snapshot',
    ];

    protected $casts = [
        'tanggal_riwayat_transaksi' => 'datetime',
    ];

    public function getCreatedAtAttribute()
    {
        return $this->tanggal_riwayat_transaksi;
    }

    // ── Accessor: nama pembuat (snapshot-first) ───────────────────────────────
    // Walau admin sudah ganti username, invoice lama tetap menampilkan nama lama
    public function getNamaPembuatAttribute(): string
    {
        return $this->username_snapshot ?? $this->user?->username ?? '-';
    }

    public function getEmailPembuatAttribute(): string
    {
        return $this->email_snapshot ?? $this->user?->email ?? '-';
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