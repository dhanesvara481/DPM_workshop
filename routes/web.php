<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\RiwayatPerubahanStokController;
use App\Http\Controllers\RiwayatTransaksiController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\JadwalKerjaController;
use App\Http\Controllers\ManajemenStafController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StokRealtimeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\NotifikasiController;

/*
|--------------------------------------------------------------------------
| Guest Routes (belum login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'validasiLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'simpanData'])->name('login.attempt');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (wajib login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check.status'])->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard lama (redirect ke tampilan_dashboard)
    Route::get('/dashboard', function () {
        return redirect()->route('tampilan_dashboard');
    })->name('dashboard');

    //============== Mengelola Barang =================//
    Route::get('/tampilan_barang',  [BarangController::class, 'getBarang'])->name('mengelola_barang');

    Route::get('/tambah_barang',    [BarangController::class, 'getTambahBarang'])->name('tambah_barang');
    Route::post('/simpan_barang',   [BarangController::class, 'simpanBarang'])->name('simpan_barang');
    Route::get('/barang/buat_kode_barang', [BarangController::class, 'buatKodeBarang'])->name('buat_kode_barang');

    Route::get('/ubah_barang/{id}',       [BarangController::class, 'getUbahBarang'])->name('ubah_barang');
    Route::post('/perbarui_barang/{id}',  [BarangController::class, 'perbaruiBarang'])->name('perbarui_barang');

    Route::delete('/hapus_barang/{id}',   [BarangController::class, 'hapusBarang'])->name('hapus_barang');

    //============== Barang Keluar =================//
    Route::get('/barang_keluar', [BarangKeluarController::class, 'getBarangKeluar'])->name('barang_keluar');

    //============== Barang Masuk =================//
    Route::get('/barang_masuk',         [BarangMasukController::class, 'getBarangMasuk'])->name('barang_masuk');
    Route::post('/barang_masuk/simpan', [BarangMasukController::class, 'simpanBarangMasuk'])->name('simpan_barang_masuk');

    //============== Stok =================//
    Route::get('/stok_realtime',          [StokRealtimeController::class, 'getStokRealtime'])->name('stok_realtime');
    Route::get('/riwayat_perubahan_stok', [RiwayatPerubahanStokController::class, 'getRiwayatPerubahanStok'])->name('riwayat_perubahan_stok');

    //============== Transaksi =================//
    Route::get('/riwayat_transaksi',        [RiwayatTransaksiController::class, 'getRiwayatTransaksi'])->name('riwayat_transaksi');
    Route::get('/detail_riwayat_transaksi', [RiwayatTransaksiController::class, 'getDetailRiwayatTransaksi'])->name('detail_riwayat_transaksi');
    Route::get('/print_transaksi',          [RiwayatTransaksiController::class, 'nota'])->name('print_transaksi');

    //============== Invoice =================//
    Route::get('/tampilan_invoice', [InvoiceController::class, 'getTampilanInvoice'])->name('tampilan_invoice');

    //============== Laporan =================//
    Route::get('/laporan_penjualan', [LaporanPenjualanController::class, 'getLaporanPenjualan'])->name('laporan_penjualan');

    //============== Jadwal Kerja =================//
    Route::get('/kelola_jadwal_kerja',   [JadwalKerjaController::class, 'getKelolaJadwalKerja'])->name('kelola_jadwal_kerja');
    Route::get('/tambah_jadwal_kerja',   [JadwalKerjaController::class, 'getTambahJadwalKerja'])->name('tambah_jadwal_kerja');
    Route::get('/ubah_jadwal_kerja',     [JadwalKerjaController::class, 'getUbahJadwalKerja'])->name('ubah_jadwal_kerja');
    Route::get('/hapus_jadwal_kerja',    [JadwalKerjaController::class, 'getHapusJadwalKerja'])->name('hapus_jadwal_kerja');
    Route::get('/tampilan_jadwal_kerja', [JadwalKerjaController::class, 'getTampilanJadwalKerja'])->name('tampilan_jadwal_kerja');

    //============== Manajemen Staf =================//
    Route::get('/tampilan_manajemen_staf', [ManajemenStafController::class, 'getTampilanManajemenStaf'])->name('tampilan_manajemen_staf');
    Route::get('/tambah_staf',             [ManajemenStafController::class, 'getTambahStaf'])->name('tambah_staf');
    Route::get('/ubah_staf',               [ManajemenStafController::class, 'getUbahStaf'])->name('ubah_staf');
    Route::get('/detail_staf',             [ManajemenStafController::class, 'getDetailStaf'])->name('detail_staf');

    //============== Dashboard =================//
    Route::get('/tampilan_dashboard', [DashboardController::class, 'getTampilanDashboard'])->name('tampilan_dashboard');

    //============== Notifikasi =================//
    Route::get('/tampilan_notifikasi', [NotifikasiController::class, 'getTampilanNotifikasi'])->name('tampilan_notifikasi');
    Route::get('/detail_notifikasi',   [NotifikasiController::class, 'getDetailNotifikasi'])->name('detail_notifikasi');
});