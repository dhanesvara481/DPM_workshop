<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Stok Opname — audit fisik stok
 *
 * stok_opname        : sesi opname (header)
 * detail_stok_opname : per-barang dalam satu sesi
 *
 * Flow:
 *  1. Admin buat sesi + assign staff → status: draft → stok freeze otomatis
 *  2. Staff yang di-assign input stok_fisik per barang → selisih dihitung otomatis
 *  3. Staff submit → status: menunggu_approval
 *  4. Admin approve → status: disetujui → stok diupdate + freeze dicabut
 *     atau tolak    → status: ditolak   → stok tidak berubah + freeze dicabut
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Header sesi opname ────────────────────────────────────────────
        Schema::create('stok_opname', function (Blueprint $table) {
            $table->id('opname_id');

            // Admin yang membuat sesi
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                  ->references('user_id')->on('user')
                  ->onDelete('set null');

            // Staff yang di-assign untuk mengisi stok fisik
            // Nullable — kalau null berarti admin sendiri yang isi
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->foreign('assigned_to')
                  ->references('user_id')->on('user')
                  ->onDelete('set null');
            $table->string('assignee_username_snapshot', 20)->nullable();

            $table->date('tanggal_opname');
            $table->string('keterangan', 255)->nullable();

            // draft → menunggu_approval → disetujui / ditolak
            $table->enum('status', ['draft', 'menunggu_approval', 'disetujui', 'ditolak'])
                  ->default('draft');

            // Snapshot admin pembuat
            $table->string('username_snapshot', 20)->nullable();
            $table->string('email_snapshot', 100)->nullable();

            // Timestamp approve/tolak + siapa yang approve
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')
                  ->references('user_id')->on('user')
                  ->onDelete('set null');
            $table->string('approver_username_snapshot', 20)->nullable();
            $table->datetime('approved_at')->nullable();
            $table->string('catatan_approval', 255)->nullable();

            $table->timestamps();
        });

        // ── 2. Detail per-barang dalam satu sesi ────────────────────────────
        Schema::create('detail_stok_opname', function (Blueprint $table) {
            $table->id('detail_opname_id');

            $table->unsignedBigInteger('opname_id');
            $table->foreign('opname_id')
                  ->references('opname_id')->on('stok_opname')
                  ->onDelete('cascade');

            // Barang — nullable agar tidak hilang jika barang dihapus
            $table->unsignedBigInteger('barang_id')->nullable();
            $table->foreign('barang_id')
                  ->references('barang_id')->on('barang')
                  ->onDelete('set null');

            // Snapshot barang saat opname
            $table->string('kode_barang_snapshot', 50)->nullable();
            $table->string('nama_barang_snapshot', 100)->nullable();
            $table->string('satuan_snapshot', 20)->nullable();

            // Stok menurut sistem saat sesi opname dibuat
            $table->integer('stok_sistem')->default(0);

            // Stok fisik hasil hitung manual
            $table->integer('stok_fisik')->nullable();

            // Selisih = stok_fisik - stok_sistem (negatif = kurang, positif = lebih)
            // Di-generate otomatis saat stok_fisik diisi
            $table->integer('selisih')->nullable();

            // Keterangan per-item (opsional, untuk jelaskan penyebab selisih)
            $table->string('keterangan', 255)->nullable();

            // Status per-item setelah approve:
            // balance = tidak ada selisih, adjusted = stok sudah disesuaikan
            $table->enum('item_status', ['pending', 'balance', 'adjusted'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_stok_opname');
        Schema::dropIfExists('stok_opname');
    }
};