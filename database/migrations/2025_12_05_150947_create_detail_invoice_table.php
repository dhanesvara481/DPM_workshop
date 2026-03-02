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
        Schema::create('detail_invoice', function (Blueprint $table) {
            $table->id('detail_invoice_id');
            $table->unsignedBigInteger('barang_id')->nullable();
            $table->foreign('barang_id')->references('barang_id')->on('barang')->onDelete('cascade');
            $table->foreignId('invoice_id')->constrained('invoice', 'invoice_id')->onDelete('cascade');
            $table->string('nama_pelanggan', 100)->nullable();
            $table->string('kontak', 15)->nullable();
            $table->string('jumlah', 10);
            $table->decimal('total', 12,2);
            $table->string('deskripsi', 100);
            $table->enum('tipe_transaksi', ['Barang', 'Jasa']);
            $table->unsignedTinyInteger('pajak')->nullable();
            $table->decimal('diskon', 12,2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_invoice');
    }
};
