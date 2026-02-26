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
        Schema::create('invoice', function (Blueprint $table) {
            $table->id('invoice_id');
            $table->foreignId('user_id')->constrained('user', 'user_id')->onDelete('cascade');
            $table->date('tanggal_invoice');
            $table->decimal('subtotal', 12,2);
            $table->decimal('subtotal_barang', 12,2);
            $table->decimal('biaya_jasa', 12,2);
            $table->enum('status', ['Pending', 'Paid'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};
