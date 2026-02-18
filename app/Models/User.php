<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user';

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'kontak',
        'status',
        'catatan',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Scope: hanya user yang aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Helper: cek apakah user adalah admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Helper: cek apakah user adalah staff
     */
    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }
}