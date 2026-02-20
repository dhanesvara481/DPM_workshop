<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoice';
    protected $primaryKey = 'invoice_id';

    protected $fillable = [
        'user_id',
        'tanggal_invoice',
        'subtotal',
        'subtotal_barang',
        'biaya_jasa',
    ];

    protected $casts = [
        'tanggal_invoice' => 'date',
        'subtotal'        => 'decimal:2',
        'subtotal_barang' => 'decimal:2',
        'biaya_jasa'      => 'decimal:2',
    ];

    /*------------------------------------------------------------
    | Accessor: $invoice->total → nilai subtotal
    | Blade memanggil $trx->total, kita sediakan via accessor.
    ------------------------------------------------------------*/
    public function getTotalAttribute(): float
    {
        return (float) $this->subtotal;
    }

    /*------------------------------------------------------------
    | Accessor: $invoice->nama_pengguna → nama dari relasi user
    ------------------------------------------------------------*/
    public function getNamaPenggunaAttribute(): string
    {
        return $this->user?->nama ?? $this->user?->name ?? 'User';
    }

    /*------------------------------------------------------------
    | Relasi ke detail_invoice (item-item per invoice)
    ------------------------------------------------------------*/
    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'invoice_id');
    }

    /*------------------------------------------------------------
    | Relasi ke User
    ------------------------------------------------------------*/
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /*------------------------------------------------------------
    | Relasi ke RiwayatTransaksi
    ------------------------------------------------------------*/
    public function riwayatTransaksi()
    {
        return $this->hasOne(RiwayatTransaksi::class, 'invoice_id', 'invoice_id');
    }
}