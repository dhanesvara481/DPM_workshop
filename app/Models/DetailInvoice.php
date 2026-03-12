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
     *
     * Menggunakan bcmath untuk presisi desimal yang akurat.
     * Return string agar aman dipakai langsung di kalkulasi bcmath berikutnya.
     *
     * @return string  Contoh: "25000.00"
     */
    public function getHargaAttribute(): string
    {
        $hargaSatuan = (string) ($this->harga_satuan ?? '0');

        // Kalau kolom harga_satuan ada dan terisi, pakai itu (snapshot)
        if (bccomp($hargaSatuan, '0', 2) > 0) {
            return $hargaSatuan;
        }

        // Fallback: kalkulasi dari total ÷ qty (data lama)
        $qty   = (int) $this->jumlah;
        $total = (string) ($this->total ?? '0');

        if ($qty > 0) {
            return bcdiv($total, (string) $qty, 2);
        }

        return $total;
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
     * Gunakan accessor getNamaBarangAttribute() untuk tampilan.
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }
}