<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table      = 'invoice';
    protected $primaryKey = 'invoice_id';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'tanggal_invoice',
        'subtotal_barang',
        'biaya_jasa',
        'subtotal',
        'status',
        'tanggal_bayar',
    ];

    protected $attributes = [
        'status'          => 'Pending',
        'subtotal_barang' => 0,
        'biaya_jasa'      => 0,
        'subtotal'        => 0,
    ];

    protected $casts = [
        'subtotal_barang' => 'decimal:2',
        'biaya_jasa'      => 'decimal:2',
        'subtotal'        => 'decimal:2',
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
        return $this->hasMany(DetailInvoice::class, 'invoice_id', 'invoice_id');
    }

    public function riwayatTransaksi()
    {
        return $this->hasOne(RiwayatTransaksi::class, 'invoice_id', 'invoice_id');
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    /**
     * Diambil dari item pertama yang punya nilai (skip row catatan yang null)
     */
    public function getNamaPelangganAttribute(): ?string
    {
        return $this->items->first(fn($i) => !is_null($i->nama_pelanggan))?->nama_pelanggan;
    }

    public function getKontakAttribute(): ?string
    {
        return $this->items->first(fn($i) => !is_null($i->kontak))?->kontak;
    }

    /**
     * Row ringkasan = row dengan barang_id null, jumlah = '0', total = 0,
     * dan diskon/pajak terisi. Disimpan oleh controller saat store().
     */
    public function getRingkasanAttribute(): ?DetailInvoice
    {
        return $this->items->first(
            fn($i) => is_null($i->barang_id)
                   && (int) $i->jumlah === 0
                   && (!is_null($i->diskon) || !is_null($i->pajak))
        );
    }

    /**
     * grand_total dihitung dari subtotal invoice + pajak - diskon
     * yang tersimpan di row ringkasan detail_invoice.
     *
     * Menggunakan bcmath untuk presisi desimal yang akurat —
     * menghindari floating point error pada kalkulasi nilai uang.
     *
     * @return string  Nilai dalam format string desimal (e.g. "150000.00")
     */
    public function getGrandTotalAttribute(): string
    {
        $subtotal  = (string) ($this->subtotal ?? '0');
        $ringkasan = $this->ringkasan;

        $diskon   = (string) ($ringkasan?->diskon ?? '0');
        $pajakPct = (string) ($ringkasan?->pajak  ?? '0');

        // afterDisc = max(0, subtotal - diskon)
        $selisih   = bcsub($subtotal, $diskon, 2);
        $afterDisc = bccomp($selisih, '0', 2) >= 0 ? $selisih : '0.00';

        // pajakVal = round(afterDisc * pajakPct / 100, 2)
        $pajakRaw = bcdiv(bcmul($afterDisc, $pajakPct, 6), '100', 6);
        $pajakVal = number_format((float) $pajakRaw, 2, '.', '');

        return bcadd($afterDisc, $pajakVal, 2);
    }

    /**
     * Kategori ditentukan dari tipe_transaksi:
     * - ada row Jasa dengan barang_id null DAN jumlah > 0  => 'jasa'
     * - semua row adalah Barang (atau row ringkasan jumlah=0) => 'barang'
     *
     * Row ringkasan/catatan (barang_id=null, jumlah='0') tidak dihitung.
     */
    public function getKategoriAttribute(): string
    {
        return $this->items->contains(
            fn($i) => $i->tipe_transaksi === 'Jasa'
                   && is_null($i->barang_id)
                   && (int) $i->jumlah > 0
        ) ? 'jasa' : 'barang';
    }
}