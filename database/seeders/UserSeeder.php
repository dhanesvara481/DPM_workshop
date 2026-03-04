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
                'username' => 'Yudha',
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
                'username' => 'sina',
                'password' => Hash::make('sina123'),
                'role'     => 'staff',
                'kontak'   => '081805481158',
                'email'    => 'abhisina08@gmail.com',
                'status'   => 'aktif',
                'catatan'  => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'udit',
                'password' => Hash::make('udit123'),
                'role'     => 'staff',
                'kontak'   => '08970998272',
                'email'    => 'mahayasa.udit@gmail.com',
                'status'   => 'aktif',
                'catatan'  => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}