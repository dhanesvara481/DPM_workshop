<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $dropFk = function (string $tabel, string $kolom) {
            $fks = DB::select("
                SELECT kcu.CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE kcu
                JOIN information_schema.REFERENTIAL_CONSTRAINTS rc
                  ON rc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME
                 AND rc.CONSTRAINT_SCHEMA = kcu.TABLE_SCHEMA
                WHERE kcu.TABLE_SCHEMA = DATABASE()
                  AND kcu.TABLE_NAME   = ?
                  AND kcu.COLUMN_NAME  = ?
            ", [$tabel, $kolom]);

            foreach ($fks as $fk) {
                DB::statement("ALTER TABLE `{$tabel}` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
            }
        };

        // 1. barang_masuk
        $dropFk('barang_masuk', 'barang_id');
        Schema::table('barang_masuk', fn(Blueprint $t) =>
            $t->unsignedBigInteger('barang_id')->nullable()->change()
        );
        DB::statement("ALTER TABLE `barang_masuk` ADD CONSTRAINT `fk_bm_barang_id`
            FOREIGN KEY (`barang_id`) REFERENCES `barang`(`barang_id`) ON DELETE SET NULL ON UPDATE CASCADE");

        // 2. barang_keluar
        $dropFk('barang_keluar', 'barang_id');
        Schema::table('barang_keluar', fn(Blueprint $t) =>
            $t->unsignedBigInteger('barang_id')->nullable()->change()
        );
        DB::statement("ALTER TABLE `barang_keluar` ADD CONSTRAINT `fk_bk_barang_id`
            FOREIGN KEY (`barang_id`) REFERENCES `barang`(`barang_id`) ON DELETE SET NULL ON UPDATE CASCADE");

        // 3. riwayat_stok
        $dropFk('riwayat_stok', 'barang_id');
        Schema::table('riwayat_stok', fn(Blueprint $t) =>
            $t->unsignedBigInteger('barang_id')->nullable()->change()
        );
        DB::statement("ALTER TABLE `riwayat_stok` ADD CONSTRAINT `fk_rs_barang_id`
            FOREIGN KEY (`barang_id`) REFERENCES `barang`(`barang_id`) ON DELETE SET NULL ON UPDATE CASCADE");

        // 4. detail_invoice
        $dropFk('detail_invoice', 'barang_id');
        DB::statement("ALTER TABLE `detail_invoice` ADD CONSTRAINT `fk_di_barang_id`
            FOREIGN KEY (`barang_id`) REFERENCES `barang`(`barang_id`) ON DELETE SET NULL ON UPDATE CASCADE");
    }

    public function down(): void {}
};