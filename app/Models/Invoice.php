<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table      = 'invoice';
    protected $primaryKey = 'invoice_id';

    public $timestamps = true;

    // Hanya kolom yang ada di migration tabel invoice
    protected $fillable = [
        'user_id',
        'tanggal_invoice',
        'subtotal',
        'subtotal_barang',
        'biaya_jasa',
        'status',
        'tanggal_bayar',
    ];
    // Di Invoice model, tambahkan:
    protected $attributes = [
        'status' => 'Pending',
    ];

    protected $casts = [
        'subtotal'        => 'decimal:2',
        'subtotal_barang' => 'decimal:2',
        'biaya_jasa'      => 'decimal:2',
        'tanggal_invoice' => 'datetime',
        'tanggal_bayar'   => 'datetime',
    ];

    // ── Relasi ───────────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'invoice_id');
    }

    public function riwayatTransaksi()
    {
        return $this->hasOne(RiwayatTransaksi::class, 'invoice_id', 'invoice_id');
    }

    // ── Accessors ─────────────────────────────────────────────────────────────
    // nama_pelanggan & kontak tidak ada di tabel invoice,
    // diambil dari detail_invoice (item pertama).

    public function getNamaPelangganAttribute(): ?string
    {
        return $this->items->first()?->nama_pelanggan;
    }

    public function getKontakAttribute(): ?string
    {
        return $this->items->first()?->kontak;
    }

    /**
     * Kategori ditentukan dari tipe_transaksi di detail_invoice:
     * - ada row dengan barang_id null (row jasa murni) => 'jasa'
     * - semua row punya barang_id                      => 'barang'
     */
    public function getKategoriAttribute(): string
    {
        return $this->items->contains(fn($i) => is_null($i->barang_id)) ? 'jasa' : 'barang';
    }
    
}