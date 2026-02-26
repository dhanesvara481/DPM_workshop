<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table      = 'invoice';
    protected $primaryKey = 'invoice_id';

    public $timestamps = false;

    // Daftar atribut yang dapat diisi secara massal
    protected $fillable = [
        'user_id',
        'tanggal_invoice',
        'subtotal',
        'subtotal_barang',
        'biaya_jasa',
    ];

    // Formating atribut untuk kemudahan penggunaan
    protected $casts = [
        'tanggal_invoice' => 'date',
        'subtotal'        => 'decimal:2',
        'subtotal_barang' => 'decimal:2',
        'biaya_jasa'      => 'decimal:2',
    ];
    
    public function getTotalAttribute(): float
    {
        return (float) $this->subtotal;
    }

    public function getNamaPenggunaAttribute(): string
    {
        return $this->user?->username ?? $this->user?->nama ?? $this->user?->name ?? 'User';
    }
    
    // 
    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'invoice_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function riwayatTransaksi()
    {
        return $this->hasOne(RiwayatTransaksi::class, 'invoice_id', 'invoice_id');
    }
    
    public function detailInvoice()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'invoice_id');
    }
}