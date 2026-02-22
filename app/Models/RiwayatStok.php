<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatStok extends Model
{
    use HasFactory;

    protected $table      = 'riwayat_stok';
    protected $primaryKey = 'riwayat_stok_id';

    public $timestamps = false;

    protected $fillable = [
        'barang_id',
        'user_id',
        'barang_masuk_id',
        'barang_keluar_id',
        'tanggal_riwayat_stok',
        'stok_awal',
        'stok_akhir',
    ];

    protected $casts = [
        'tanggal_riwayat_stok' => 'date',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────────

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class, 'barang_masuk_id', 'barang_masuk_id');
    }

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class, 'barang_keluar_id', 'barang_keluar_id');
    }

    // ── Accessor: tipe masuk / keluar ────────────────────────────────────────

    public function getTipeAttribute(): string
    {
        return $this->barang_masuk_id ? 'masuk' : 'keluar';
    }

    // ── Accessor: qty dari relasi yang aktif ─────────────────────────────────

    public function getQtyAttribute(): int
    {
        if ($this->barang_masuk_id) {
            return (int) ($this->barangMasuk?->jumlah_masuk ?? 0);
        }
        if ($this->barang_keluar_id) {
            return (int) ($this->barangKeluar?->jumlah_keluar ?? 0);
        }
        return 0;
    }

    // ── Accessor: keterangan — dibuat langsung, TIDAK disimpan ke DB ─────────
    // Jika dari invoice  → "Invoice INV-{id}"
    // Jika barang keluar biasa → keterangan dari tabel barang_keluar (jika ada)
    // Jika barang masuk  → "Barang Masuk"

    public function getKeteranganAttribute(): string
    {
        if ($this->barang_masuk_id) {
            return 'Barang Masuk';
        }

        if ($this->barang_keluar_id) {
            $keluar = $this->barangKeluar;

            if (!empty($keluar?->ref_invoice)) {
                return 'Invoice INV-' . $keluar->ref_invoice;
            }

            return $keluar?->keterangan ?? 'Barang Keluar';
        }

        return '-';
    }
}