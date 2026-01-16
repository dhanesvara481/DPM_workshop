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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id('notifikasi_id');
            $table->enum('jenis_notifikasi', ['info', 'jadwal', 'invoice', 'stok', 'peringatan']);
            $table->string('isi_pesan', 150);
            $table->enum('status_dibaca',['belum_dibaca', 'dibaca']);
            $table->enum('status_kirim',['gagal', 'terkirim']);
            $table->dateTime('tanggal_dibuat');
            $table->dateTime('tanggal_dikirim');
            $table->string('judul_notif', 30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
