<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $table = 'detail_invoice';
    protected $primaryKey = 'detail_invoice_id';

    protected $fillable = [
        'invoice_id',
        'barang_id',
        'jumlah',
        'total',
        'deskripsi',
        'tipe_transaksi',  // 'Barang' | 'Jasa'
    ];

    protected $casts = [
        'total'  => 'decimal:2',
        'jumlah' => 'integer',
    ];

    /*------------------------------------------------------------
    | Accessor: blade memanggil $it->nama_barang
    | → ambil dari kolom deskripsi (nama item di migration)
    ------------------------------------------------------------*/
    public function getNamaBarangAttribute(): string
    {
        return $this->deskripsi ?? $this->barang?->nama_barang ?? '-';
    }

    /*------------------------------------------------------------
    | Accessor: blade memanggil $it->harga
    | Harga satuan = total / jumlah  (jika jumlah > 0)
    ------------------------------------------------------------*/
    public function getHargaAttribute(): float
    {
        $qty = (int) $this->jumlah;
        return $qty > 0 ? (float) $this->total / $qty : (float) $this->total;
    }

    /*------------------------------------------------------------
    | Accessor: blade memanggil $it->qty
    ------------------------------------------------------------*/
    public function getQtyAttribute(): int
    {
        return (int) $this->jumlah;
    }

    /*------------------------------------------------------------
    | Relasi ke Invoice
    ------------------------------------------------------------*/
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'invoice_id');
    }

    /*------------------------------------------------------------
    | Relasi ke Barang
    ------------------------------------------------------------*/
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }
}