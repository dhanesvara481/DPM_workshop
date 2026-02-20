<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatStok extends Model
{
    use HasFactory;

    protected $table      = 'riwayat_stok';
    protected $primaryKey = 'riwayat_stok_id';

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


    // tipe: 'masuk' atau 'keluar'
    public function getTipeAttribute(): string
    {
        return $this->barang_masuk_id ? 'masuk' : 'keluar';
    }

    // qty: jumlah dari relasi yang aktif
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

    // keterangan: masuk → 'Barang Masuk', keluar → dari tabel barang_keluar
    public function getKeteranganAttribute(): string
    {
        if ($this->barang_masuk_id) {
            return 'Barang Masuk';
        }
        if ($this->barang_keluar_id) {
            return $this->barangKeluar?->keterangan ?? '-';
        }
        return '-';
    }
}