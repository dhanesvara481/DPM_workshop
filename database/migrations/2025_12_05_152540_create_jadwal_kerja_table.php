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
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('user_id')->on('user')->onDelete('set null');

            $table->date('tanggal_kerja');

            $table->enum('waktu_shift', ['Pagi', 'Siang', 'Sore', 'Malam'])->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('deskripsi', 100)->nullable();
            $table->enum('status', ['Aktif', 'Catatan', 'Tutup'])->default('Aktif');
            $table->timestamps();

            // Tidak ada unique constraint di sini.
            // Validasi duplikat untuk status Aktif ditangani di application level (controller).
            // Status Catatan boleh duplikat user+tanggal (bisa ada 2+ catatan oleh admin yg sama).
            // Status Tutup pakai user_id = null sehingga tidak bentrok.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_kerja');
    }
};