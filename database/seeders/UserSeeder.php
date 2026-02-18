<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user')->insert([
            // ── ADMIN ──────────────────────────────────────────────
            [
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
                'kontak'   => '081234567890',
                'email'    => 'admin@dpwworkshop.com',
                'status'   => 'aktif',
                'catatan'  => 'Super Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ── STAFF ──────────────────────────────────────────────
            [
                'username' => 'budi',
                'password' => Hash::make('budi123'),
                'role'     => 'staff',
                'kontak'   => '082345678901',
                'email'    => 'budi@dpwworkshop.com',
                'status'   => 'aktif',
                'catatan'  => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'sari',
                'password' => Hash::make('sari123'),
                'role'     => 'staff',
                'kontak'   => '083456789012',
                'email'    => 'sari@dpwworkshop.com',
                'status'   => 'aktif',
                'catatan'  => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'doni',
                'password' => Hash::make('doni123'),
                'role'     => 'staff',
                'kontak'   => '084567890123',
                'email'    => 'doni@dpwworkshop.com',
                'status'   => 'nonaktif',
                'catatan'  => 'Sedang cuti panjang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}