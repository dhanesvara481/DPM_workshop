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
        Schema::create('jadwal_kerja', function (Blueprint $table) {
            $table->id('jadwal_id');
            $table->foreignId('user_id')->constrained('user', 'user_id')->onDelete('cascade');
            $table->date('tanggal_kerja');
            $table->enum('waktu_shift', ['Pagi', 'Siang', 'Sore', 'Malam']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('deskripsi', 100);
            $table->enum('status', ['Aktif', 'Catatan', 'Tutup']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_kerja');
    }
};
