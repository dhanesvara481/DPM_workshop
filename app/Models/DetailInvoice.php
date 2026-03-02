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
        'deskripsi',      // snapshot nama barang — tidak hilang walau barang dihapus
        'harga_satuan',   // snapshot harga per satuan saat invoice dibuat
        'jumlah',
        'total',
        'tipe_transaksi',
        'diskon',         // Rp — hanya diisi di row ringkasan
        'pajak',          // %  — hanya diisi di row ringkasan
    ];

    protected $casts = [
        'total'        => 'decimal:2',
        'harga_satuan' => 'decimal:2',
        'diskon'       => 'decimal:2',
        'pajak'        => 'integer',
    ];

    // ── Accessors ────────────────────────────────────────────────────────────

    /**
     * Nama barang: prioritas snapshot `deskripsi`, fallback ke relasi barang.
     * Tidak akan kosong walau barang sudah dihapus dari DB.
     */
    public function getNamaBarangAttribute(): string
    {
        return $this->deskripsi ?? $this->barang?->nama_barang ?? '-';
    }

    /**
     * Harga per satuan: prioritas kolom snapshot `harga_satuan`,
     * fallback kalkulasi dari total/jumlah (data lama sebelum migrasi).
     */
    public function getHargaAttribute(): float
    {
        // Kalau kolom harga_satuan ada dan terisi, pakai itu (snapshot)
        if (!is_null($this->harga_satuan) && (float) $this->harga_satuan > 0) {
            return (float) $this->harga_satuan;
        }

        // Fallback: kalkulasi dari total ÷ qty (data lama)
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

    /**
     * Relasi ke barang — bisa null jika barang sudah dihapus.
     * Gunakan accessor getNamaBarangAttribute() untuk tampilan, bukan langsung $item->barang->nama_barang.
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }
}