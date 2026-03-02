<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // BARANG MASUK
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->string('kode_barang_snapshot')->nullable()->after('barang_id');
            $table->string('nama_barang_snapshot')->nullable()->after('kode_barang_snapshot');
            $table->string('satuan_snapshot')->nullable()->after('nama_barang_snapshot');
        });

        // BARANG KELUAR
        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->string('kode_barang_snapshot')->nullable()->after('barang_id');
            $table->string('nama_barang_snapshot')->nullable()->after('kode_barang_snapshot');
            $table->string('satuan_snapshot')->nullable()->after('nama_barang_snapshot');
        });

        // RIWAYAT STOK
        Schema::table('riwayat_stok', function (Blueprint $table) {
            $table->string('kode_barang_snapshot')->nullable()->after('barang_id');
            $table->string('nama_barang_snapshot')->nullable()->after('kode_barang_snapshot');
        });
    }

    public function down(): void
    {
        Schema::table('barang_masuk', fn(Blueprint $t) =>
            $t->dropColumn(['kode_barang_snapshot','nama_barang_snapshot','satuan_snapshot'])
        );

        Schema::table('barang_keluar', fn(Blueprint $t) =>
            $t->dropColumn(['kode_barang_snapshot','nama_barang_snapshot','satuan_snapshot'])
        );

        Schema::table('riwayat_stok', fn(Blueprint $t) =>
            $t->dropColumn(['kode_barang_snapshot','nama_barang_snapshot'])
        );
    }
};