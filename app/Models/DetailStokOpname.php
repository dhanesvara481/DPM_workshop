<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailStokOpname extends Model
{
    protected $table      = 'detail_stok_opname';
    protected $primaryKey = 'detail_opname_id';

    protected $fillable = [
        'opname_id',
        'barang_id',
        'kode_barang_snapshot',
        'nama_barang_snapshot',
        'satuan_snapshot',
        'stok_sistem',
        'stok_fisik',
        'selisih',
        'keterangan',
        'item_status',
    ];

    protected $casts = [
        'stok_sistem' => 'integer',
        'stok_fisik'  => 'integer',
        'selisih'     => 'integer',
    ];

    // ── Relasi ───────────────────────────────────────────────────────────────

    public function opname(): BelongsTo
    {
        return $this->belongsTo(StokOpname::class, 'opname_id', 'opname_id');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    public function getKodeBarangAttribute(): string
    {
        return $this->kode_barang_snapshot ?? $this->barang?->kode_barang ?? '[Dihapus]';
    }

    public function getNamaBarangAttribute(): string
    {
        return $this->nama_barang_snapshot ?? $this->barang?->nama_barang ?? '[Barang Dihapus]';
    }

    public function getSatuanAttribute(): string
    {
        return $this->satuan_snapshot ?? $this->barang?->satuan ?? '-';
    }

    /**
     * Label selisih untuk tampilan:
     * +5 = lebih, -3 = kurang, 0 = balance
     */
    public function getSelisihLabelAttribute(): string
    {
        if (is_null($this->selisih)) return '-';
        if ($this->selisih > 0) return '+' . $this->selisih;
        return (string) $this->selisih;
    }

    public function getSelisihBadgeClassAttribute(): string
    {
        if (is_null($this->selisih) || $this->selisih === 0) {
            return 'bg-emerald-100 text-emerald-700';
        }
        return $this->selisih > 0
            ? 'bg-blue-100 text-blue-700'
            : 'bg-rose-100 text-rose-700';
    }

    public function getHasSelisihAttribute(): bool
    {
        return !is_null($this->selisih) && $this->selisih !== 0;
    }
}