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
                'kontak'   => '081805481158',
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
                'username' => 'prayoga',
                'password' => Hash::make('prayoga123'),
                'role'     => 'staff',
                'kontak'   => '081558129904',
                'email'    => 'prayogadev145@gmail.com',
                'status'   => 'aktif',
                'catatan'  => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'saka',
                'password' => Hash::make('saka123'),
                'role'     => 'staff',
                'kontak'   => '087759045166',
                'email'    => 'sakaxd@gmail.com',
                'status'   => 'aktif',
                'catatan'  => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'satria',
                'password' => Hash::make('satria123'),
                'role'     => 'staff',
                'kontak'   => '085173005420',
                'email'    => 'satriahighvoltage@gmail.com',
                'status'   => 'aktif',
                'catatan'  => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'angel',
                'password' => Hash::make('angel123'),
                'role'     => 'staff',
                'kontak'   => '085738045266',
                'email'    => 'angeljmx745@gmail.com',
                'status'   => 'aktif',
                'catatan'  => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}