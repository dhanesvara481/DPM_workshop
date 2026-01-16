<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user'; // 👈 PENTING

    protected $primaryKey = 'user_id'; // kalau PK kamu user_id

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'kontak',
    ];

    protected $hidden = [
        'password',
    ];
}
