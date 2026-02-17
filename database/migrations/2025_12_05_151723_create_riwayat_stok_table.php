<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riwayat_stok', function (Blueprint $table) {
            $table->id('riwayat_stok_id');
            $table->foreignId('barang_id')->constrained('barang', 'barang_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('user', 'user_id')->onDelete('cascade');
            $table->foreignId('barang_masuk_id')->constrained('barang_masuk', 'barang_masuk_id')->onDelete('cascade');
            $table->foreignId('barang_keluar_id')->constrained('barang_keluar', 'barang_keluar_id')->onDelete('cascade');
            $table->date('tanggal_riwayat_stok');
            $table->string('stok_awal', 10);
            $table->string('stok_akhir', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_stok');
    }
};
