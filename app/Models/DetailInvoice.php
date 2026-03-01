<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailInvoice extends Model
{
    protected $table      = 'detail_invoice';
    protected $primaryKey = 'detail_invoice_id';

    public $timestamps = true;

    protected $fillable = [
        'invoice_id',
        'barang_id',
        'nama_pelanggan',
        'kontak',
        'deskripsi',
        'jumlah',
        'total',
        'tipe_transaksi',
        'diskon', // Rp — hanya diisi di row ringkasan
        'pajak',  // %  — hanya diisi di row ringkasan
    ];

    protected $casts = [
        'total'  => 'decimal:2',
        'diskon' => 'decimal:2',
        'pajak'  => 'integer',
    ];

    // ── Accessors ────────────────────────────────────────────────────────────

    public function getNamaBarangAttribute(): string
    {
        return $this->deskripsi ?? $this->barang?->nama_barang ?? '-';
    }

    public function getHargaAttribute(): float
    {
        $qty = (int) $this->jumlah;
        return $qty > 0 ? (float) $this->total / $qty : (float) $this->total;
    }

    public function getQtyAttribute(): int
    {
        return (int) $this->jumlah;
    }

    // ── Relasi ───────────────────────────────────────────────────────────────

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'invoice_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }
}