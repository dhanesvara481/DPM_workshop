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
    Schema::create('barang_masuk', function (Blueprint $table) {
        $table->id('barang_masuk_id');
        $table->foreignId('barang_id')->constrained('barang', 'barang_id')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('user', 'user_id')->onDelete('cascade');
        $table->string('jumlah_masuk', 10);
        $table->date('tanggal_masuk');
        $table->timestamps();
    });
}

/**
 * Reverse the migrations.
 */
public function down(): void
{
    Schema::dropIfExists('barang_masuk');
}
};
