<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. detail_invoice.barang_id → set null ──────────────────────────
        Schema::table('detail_invoice', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
            $table->foreign('barang_id')
                  ->references('barang_id')
                  ->on('barang')
                  ->onDelete('set null');
        });

        if (!Schema::hasColumn('detail_invoice', 'harga_satuan')) {
            Schema::table('detail_invoice', function (Blueprint $table) {
                $table->decimal('harga_satuan', 12, 2)->default(0)->after('jumlah');
            });
        }

        // ── 2. barang_masuk.barang_id → set null ────────────────────────────
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
            $table->unsignedBigInteger('barang_id')->nullable()->change(); // jadiin nullable dulu
            $table->foreign('barang_id')
                  ->references('barang_id')
                  ->on('barang')
                  ->onDelete('set null');
        });

        // ── 3. barang_keluar.barang_id → set null ───────────────────────────
        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
            $table->unsignedBigInteger('barang_id')->nullable()->change(); // jadiin nullable dulu
            $table->foreign('barang_id')
                  ->references('barang_id')
                  ->on('barang')
                  ->onDelete('set null');
        });

        // ── 4. riwayat_stok.barang_id → set null ────────────────────────────
        Schema::table('riwayat_stok', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
            $table->unsignedBigInteger('barang_id')->nullable()->change(); // jadiin nullable dulu
            $table->foreign('barang_id')
                  ->references('barang_id')
                  ->on('barang')
                  ->onDelete('set null');
        });

        // ── 5. riwayat_stok.barang_masuk_id & barang_keluar_id → set null ───
        // Sudah nullable dari awal, tinggal ganti onDelete
        Schema::table('riwayat_stok', function (Blueprint $table) {
            $table->dropForeign(['barang_masuk_id']);
            $table->foreign('barang_masuk_id')
                  ->references('barang_masuk_id')
                  ->on('barang_masuk')
                  ->onDelete('set null');

            $table->dropForeign(['barang_keluar_id']);
            $table->foreign('barang_keluar_id')
                  ->references('barang_keluar_id')
                  ->on('barang_keluar')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        // Balik semua ke cascade (urutan terbalik)

        Schema::table('riwayat_stok', function (Blueprint $table) {
            $table->dropForeign(['barang_masuk_id']);
            $table->foreign('barang_masuk_id')
                  ->references('barang_masuk_id')
                  ->on('barang_masuk')
                  ->onDelete('cascade');

            $table->dropForeign(['barang_keluar_id']);
            $table->foreign('barang_keluar_id')
                  ->references('barang_keluar_id')
                  ->on('barang_keluar')
                  ->onDelete('cascade');

            $table->dropForeign(['barang_id']);
            $table->unsignedBigInteger('barang_id')->nullable(false)->change();
            $table->foreign('barang_id')
                  ->references('barang_id')
                  ->on('barang')
                  ->onDelete('cascade');
        });

        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
            $table->unsignedBigInteger('barang_id')->nullable(false)->change();
            $table->foreign('barang_id')
                  ->references('barang_id')
                  ->on('barang')
                  ->onDelete('cascade');
        });

        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
            $table->unsignedBigInteger('barang_id')->nullable(false)->change();
            $table->foreign('barang_id')
                  ->references('barang_id')
                  ->on('barang')
                  ->onDelete('cascade');
        });

        Schema::table('detail_invoice', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
            $table->foreign('barang_id')
                  ->references('barang_id')
                  ->on('barang')
                  ->onDelete('cascade');
        });

        if (Schema::hasColumn('detail_invoice', 'harga_satuan')) {
            Schema::table('detail_invoice', function (Blueprint $table) {
                $table->dropColumn('harga_satuan');
            });
        }
    }
};