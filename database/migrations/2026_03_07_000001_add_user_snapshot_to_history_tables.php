<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah kolom user snapshot ke semua tabel historis.
 *
 * Tujuan: ketika admin/staff mengubah data diri (username/email),
 * riwayat lama tetap menampilkan nama LAMA — bukan nama baru.
 *
 * Kolom yang ditambah:
 *  - username_snapshot  (VARCHAR 20)
 *  - email_snapshot     (VARCHAR 100)
 *
 * Tabel:
 *  - barang_masuk
 *  - barang_keluar
 *  - riwayat_stok
 *  - riwayat_transaksi
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── 1. barang_masuk ───────────────────────────────────────────────────
        Schema::table('barang_masuk', function (Blueprint $table) {
            if (!Schema::hasColumn('barang_masuk', 'username_snapshot')) {
                $table->string('username_snapshot', 20)->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('barang_masuk', 'email_snapshot')) {
                $table->string('email_snapshot', 100)->nullable()->after('username_snapshot');
            }
        });

        // ── 2. barang_keluar ──────────────────────────────────────────────────
        Schema::table('barang_keluar', function (Blueprint $table) {
            if (!Schema::hasColumn('barang_keluar', 'username_snapshot')) {
                $table->string('username_snapshot', 20)->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('barang_keluar', 'email_snapshot')) {
                $table->string('email_snapshot', 100)->nullable()->after('username_snapshot');
            }
        });

        // ── 3. riwayat_stok ───────────────────────────────────────────────────
        Schema::table('riwayat_stok', function (Blueprint $table) {
            if (!Schema::hasColumn('riwayat_stok', 'username_snapshot')) {
                $table->string('username_snapshot', 20)->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('riwayat_stok', 'email_snapshot')) {
                $table->string('email_snapshot', 100)->nullable()->after('username_snapshot');
            }
        });

        // ── 4. riwayat_transaksi ──────────────────────────────────────────────
        Schema::table('riwayat_transaksi', function (Blueprint $table) {
            if (!Schema::hasColumn('riwayat_transaksi', 'username_snapshot')) {
                $table->string('username_snapshot', 20)->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('riwayat_transaksi', 'email_snapshot')) {
                $table->string('email_snapshot', 100)->nullable()->after('username_snapshot');
            }
        });

        // ── Backfill: isi snapshot dari data user yang masih ada ──────────────
        // Untuk record lama yang belum punya snapshot
        $tables = [
            'barang_masuk'      => 'barang_masuk_id',
            'barang_keluar'     => 'barang_keluar_id',
            'riwayat_stok'      => 'riwayat_stok_id',
            'riwayat_transaksi' => 'riwayat_transaksi_id',
        ];

        foreach ($tables as $tabel => $pk) {
            DB::statement("
                UPDATE `{$tabel}` t
                JOIN `user` u ON u.user_id = t.user_id
                SET
                    t.username_snapshot = u.username,
                    t.email_snapshot    = u.email
                WHERE t.username_snapshot IS NULL
            ");
        }
    }

    public function down(): void
    {
        $cols = ['username_snapshot', 'email_snapshot'];

        foreach (['barang_masuk', 'barang_keluar', 'riwayat_stok', 'riwayat_transaksi'] as $tabel) {
            Schema::table($tabel, function (Blueprint $table) use ($cols, $tabel) {
                foreach ($cols as $col) {
                    if (Schema::hasColumn($tabel, $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};