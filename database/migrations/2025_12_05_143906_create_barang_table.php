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
        Schema::create('barang', function (Blueprint $table) {
            $table->id('barang_id');
            $table->string('kode_barang', 50)->unique();
            $table->string('nama_barang', 100);
            $table->string('stok', 10);
            $table->enum('satuan', ['pcs', 'unit', 'gram', 'set']);
            $table->decimal('harga_beli',12, 2);
            $table->decimal('harga_jual', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
