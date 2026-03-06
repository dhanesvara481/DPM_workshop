<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokOpname extends Model
{
    protected $table      = 'stok_opname';
    protected $primaryKey = 'opname_id';

    protected $fillable = [
        'user_id',
        'tanggal_opname',
        'keterangan',
        'status',
        // snapshot pembuat
        'username_snapshot',
        'email_snapshot',
        // approval
        'approved_by',
        'approver_username_snapshot',
        'approved_at',
        'catatan_approval',
    ];

    protected $casts = [
        'tanggal_opname' => 'date',
        'approved_at'    => 'datetime',
    ];

    // ── Relasi ───────────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailStokOpname::class, 'opname_id', 'opname_id');
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    public function getNamaPembuatAttribute(): string
    {
        return $this->username_snapshot ?? $this->user?->username ?? '-';
    }

    public function getNamaApproverAttribute(): string
    {
        return $this->approver_username_snapshot ?? $this->approver?->username ?? '-';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'               => 'Draft',
            'menunggu_approval'   => 'Menunggu Approval',
            'disetujui'           => 'Disetujui',
            'ditolak'             => 'Ditolak',
            default               => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'draft'             => 'bg-gray-100 text-gray-600 border-gray-200',
            'menunggu_approval' => 'bg-amber-100 text-amber-700 border-amber-200',
            'disetujui'         => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            'ditolak'           => 'bg-rose-100 text-rose-700 border-rose-200',
            default             => 'bg-gray-100 text-gray-600',
        };
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isMenungguApproval(): bool
    {
        return $this->status === 'menunggu_approval';
    }

    public function isDisetujui(): bool
    {
        return $this->status === 'disetujui';
    }

    public function isDitolak(): bool
    {
        return $this->status === 'ditolak';
    }

    /**
     * Jumlah item yang memiliki selisih (stok_fisik != stok_sistem)
     */
    public function getJumlahSelisihAttribute(): int
    {
        return $this->details->filter(fn($d) => !is_null($d->selisih) && $d->selisih !== 0)->count();
    }

    /**
     * Apakah semua item sudah diisi stok fisiknya
     */
    public function getSemuaTerisiAttribute(): bool
    {
        return $this->details->every(fn($d) => !is_null($d->stok_fisik));
    }
}