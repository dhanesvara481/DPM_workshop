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
        // ── snapshot barang ──
        'kode_barang_snapshot',
        'nama_barang_snapshot',
        'satuan_snapshot',
        // ── bukti foto ──
        'foto_bukti',
        // ── snapshot user (admin/staff yang input) ──
        'username_snapshot',
        'email_snapshot',
    ];

    protected $casts = [
        'tanggal_keluar' => 'datetime',
    ];

    // ── Accessor: URL lengkap foto bukti ─────────────────────────────────────
    public function getFotoBuktiUrlAttribute(): ?string
    {
        return $this->foto_bukti
            ? asset('storage/' . $this->foto_bukti)
            : null;
    }

    // ── Accessor: nama pengguna yang bertanggung jawab ────────────────────────
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

    public function scopeSearch($query, ?string $keyword)
    {
        if (!$keyword) return $query;

        return $query->whereHas('barang', function ($q) use ($keyword) {
            $q->where('kode_barang', 'like', "%{$keyword}%")
              ->orWhere('nama_barang', 'like', "%{$keyword}%");
        });
    }
}