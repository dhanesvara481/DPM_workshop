<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_invoice', function (Blueprint $table) {
            // Drop foreign key lama yang pakai onDelete('cascade')
            $table->dropForeign(['barang_id']);

            // Buat ulang dengan onDelete('set null')
            $table->foreign('barang_id')
                  ->references('barang_id')
                  ->on('barang')
                  ->onDelete('set null');
        });

        // Pastikan kolom snapshot sudah ada
        // Kolom deskripsi sudah ada (nama barang), harga_satuan perlu ditambah
        if (!Schema::hasColumn('detail_invoice', 'harga_satuan')) {
            Schema::table('detail_invoice', function (Blueprint $table) {
                $table->decimal('harga_satuan', 12, 2)->default(0)->after('jumlah');
            });
        }
    }

    public function down(): void
    {
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


