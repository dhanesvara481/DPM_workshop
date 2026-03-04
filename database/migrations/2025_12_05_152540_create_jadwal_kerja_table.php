<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_kerja', function (Blueprint $table) {
            $table->id('jadwal_id');
            $table->foreignId('user_id')->constrained('user', 'user_id')->onDelete('cascade');

            // date (bukan datetime) — konsisten dengan model cast 'date' dan query whereDate()
            $table->date('tanggal_kerja');

            $table->enum('waktu_shift', ['Pagi', 'Siang', 'Sore', 'Malam'])->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('deskripsi', 100)->nullable();
            $table->enum('status', ['Aktif', 'Catatan', 'Tutup'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_kerja');
    }
};