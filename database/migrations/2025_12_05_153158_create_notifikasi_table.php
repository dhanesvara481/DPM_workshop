<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id('notifikasi_id');
            $table->enum('jenis_notifikasi', ['info', 'jadwal', 'invoice', 'stok', 'peringatan']);

            // VARCHAR(100) — cukup untuk judul seperti "Stok Habis: nama_barang_panjang"
            $table->string('judul_notif', 100);

            // TEXT — pesan WA/email bisa multi-line ratusan karakter
            $table->text('isi_pesan');

            $table->datetime('tanggal_dibuat');
            $table->datetime('tanggal_dikirim');
            $table->timestamps();
        });

        // utf8mb4 supaya support emoji (📅 ⚠️ 🚨) & karakter 4-byte
        // Tanpa ini insert pesan dengan emoji error SQLSTATE 22007
        DB::statement("ALTER TABLE `notifikasi`
            CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};