<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_kerja', function (Blueprint $table) {
            // 1 user hanya boleh 1 agenda per tanggal (safety net DB level)
            // Pakai DATE(tanggal_kerja) supaya work meski kolom datetime
            $table->unique(['user_id', 'tanggal_kerja'], 'unique_user_per_tanggal');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_kerja', function (Blueprint $table) {
            $table->dropUnique('unique_user_per_tanggal');
        });
    }
};