<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatStok extends Model
{
    use HasFactory;

    protected $table      = 'riwayat_stok';
    protected $primaryKey = 'riwayat_stok_id';

    public $timestamps = false;

    protected $fillable = [
        'barang_id',
        'user_id',
        'barang_masuk_id',
        'barang_keluar_id',
        'tanggal_riwayat_stok',
        'stok_awal',
        'stok_akhir',
        // ── snapshot barang ──
        'kode_barang_snapshot',
        'nama_barang_snapshot',
        // ── snapshot user (admin/staff yang bertanggung jawab) ──
        'username_snapshot',
        'email_snapshot',
    ];

    protected $casts = [
        'tanggal_riwayat_stok' => 'date',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────────

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

    // ── Accessor: nama pengguna (snapshot-first) ─────────────────────────────
    // Jika admin sudah ganti username, riwayat lama tetap tampilkan nama lama
    public function getNamaPenggunaAttribute(): string
    {
        return $this->username_snapshot ?? $this->user?->username ?? '-';
    }

    public function getEmailPenggunaAttribute(): string
    {
        return $this->email_snapshot ?? $this->user?->email ?? '-';
    }

    // ── Accessor: tipe masuk / keluar ────────────────────────────────────────

    public function getTipeAttribute(): string
    {
        return $this->barang_masuk_id ? 'masuk' : 'keluar';
    }

    // ── Accessor: qty dari relasi yang aktif ─────────────────────────────────

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

    // ── Accessor: keterangan ─────────────────────────────────────────────────

    public function getKeteranganAttribute(): string
    {
        if ($this->barang_masuk_id) {
            return 'Barang Masuk';
        }

        if ($this->barang_keluar_id) {
            $keluar = $this->barangKeluar;

            if (!empty($keluar?->ref_invoice)) {
                return 'Invoice INV-' . $keluar->ref_invoice;
            }

            return $keluar?->keterangan ?? 'Barang Keluar';
        }

        return '-';
    }
}