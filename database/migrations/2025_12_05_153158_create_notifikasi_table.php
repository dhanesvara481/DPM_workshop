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
            $table->string('isi_pesan', 150);
            $table->datetime('tanggal_dibuat');
            $table->datetime('tanggal_dikirim');
            $table->string('judul_notif', 30);
            $table->timestamps();
        });

        // Set seluruh tabel ke utf8mb4 supaya support emoji & karakter 4-byte
        DB::statement("ALTER TABLE `notifikasi`
            CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};