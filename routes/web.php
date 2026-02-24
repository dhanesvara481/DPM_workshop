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

//routes/login
Route::get('/login', [LoginController::class, 'validasiLogin'])->name('login');
Route::post('/login', [LoginController::class, 'simpanData'])->name('login.attempt');

// Contoh halaman setelah login
Route::get('/dashboard', function () {
    return 'Dashboard';
})->middleware('auth')->name('dashboard');

//Admin
//Mengelola Barang
// routes/tampilan_barang
Route::get('/tampilan_barang', [BarangController::class, 'getBarang'])->name('mengelola_barang');

//routes/tambah_barang
Route::get('/tambah_barang', [BarangController::class, 'getTambahBarang'])->name('tambah_barang');

//routes/ubah_barang
Route::get('/ubah_barang', [BarangController::class, 'getUbahBarang'])->name('ubah_barang');

//routes/barang_keluar
Route::get('/barang_keluar', [BarangKeluarController::class, 'getBarangKeluar'])->name('barang_keluar');

//routes/barang_masuk
Route::get('/barang_masuk', [BarangMasukController::class, 'getBarangMasuk'])->name('barang_masuk');

// routes/riwayat_perubahan_stok
Route::get('/riwayat_perubahan_stok', [RiwayatPerubahanStokController::class, 'getRiwayatPerubahanStok'])->name('riwayat_perubahan_stok');

// routes/riwayat_perubahan_stok
Route::get('/riwayat_transaksi', [RiwayatTransaksiController::class, 'getRiwayatTransaksi'])->name('riwayat_transaksi');

// routes/riwayat_perubahan_stok
Route::get('/detail_riwayat_transaksi', [RiwayatTransaksiController::class, 'getDetailRiwayatTransaksi'])->name('detail_riwayat_transaksi');

// routes/riwayat_perubahan_stok
Route::get('/print_transaksi', [RiwayatTransaksiController::class, 'nota'])->name('print_transaksi');

// routes/riwayat_perubahan_stok
Route::get('/laporan_penjualan', [LaporanPenjualanController::class, 'getLaporanPenjualan'])->name('laporan_penjualan');

// routes/kelola_jadwal_kerja
Route::get('/kelola_jadwal_kerja', [JadwalKerjaController::class, 'getKelolaJadwalKerja'])->name('kelola_jadwal_kerja');

// routes/tambah_jadwal_kerja
Route::get('/tambah_jadwal_kerja', [JadwalKerjaController::class, 'getTambahJadwalKerja'])->name('tambah_jadwal_kerja');

// routes/ubah_jadwal_kerja
Route::get('/ubah_jadwal_kerja', [JadwalKerjaController::class, 'getUbahJadwalKerja'])->name('ubah_jadwal_kerja');  

// routes/hapus_jadwal_kerja
Route::get('/hapus_jadwal_kerja', [JadwalKerjaController::class, 'getHapusJadwalKerja'])->name('hapus_jadwal_kerja');

// routes/tampilan_jadwal_kerja
Route::get('/tampilan_jadwal_kerja', [JadwalKerjaController::class, 'getTampilanJadwalKerja'])->name('tampilan_jadwal_kerja');

// routes/tampilan_manajemen_staf
Route::get('/tampilan_manajemen_staf', [ManajemenStafController::class, 'getTampilanManajemenStaf'])->name('tampilan_manajemen_staf'); 

// routes/tambah_staf
Route::get('/tambah_staf', [ManajemenStafController::class, 'getTambahStaf'])->name('tambah_staf'); 

// routes/ubah_staf
Route::get('/ubah_staf', [ManajemenStafController::class, 'getUbahStaf'])->name('ubah_staf');

// routes/detail_staf
Route::get('/detail_staf', [ManajemenStafController::class, 'getDetailStaf'])->name('detail_staf'); 

// routes/nonaktifkan_staf

// routes/tampilan_dashboard
Route::get('/tampilan_dashboard', [DashboardController::class, 'getTampilanDashboard'])->name('tampilan_dashboard');

// routes/stok_realtime
Route::get('/stok_realtime', [StokRealtimeController::class, 'getStokRealtime'])->name('stok_realtime');

// routes/tampilan_invoice
Route::get('/tampilan_invoice', [InvoiceController::class, 'getTampilanInvoice'])->name('tampilan_invoice'); 

// routes/tampilan_notifikasi
Route::get('/tampilan_notifikasi', [NotifikasiController::class, 'getTampilanNotifikasi'])->name('tampilan_notifikasi');

// routes/detail_notifikasi
Route::get('/detail_notifikasi', [NotifikasiController::class, 'getDetailNotifikasi'])->name('detail_notifikasi');


// ROUTES STAFFF
// routes/tampilan_dashboard_staff
Route::get('/tampilan_dashboard_staff', [DashboardController::class, 'getTampilanDashboardStaff'])->name('tampilan_dashboard_staff');

// routes/tampilan_invoice_staff
Route::get('/tampilan_invoice_staff', [InvoiceController::class, 'getTampilanInvoiceStaff'])->name('tampilan_invoice_staff');

// routes/stok_realtime_staff
Route::get('/stok_realtime_staff', [StokRealtimeController::class, 'getStokRealtimeStaff']) ->name('stok_realtime_staff');

// routes/riwayat_transaksi_staff
Route::get('/riwayat_transaksi_staff', [RiwayatTransaksiController::class, 'getRiwayatTransaksiStaff']) ->name('riwayat_transaksi_staff');

// routes/detail_riwayat_transaksi_staff
Route::get('/detail_riwayat_transaksi_staff', [RiwayatTransaksiController::class, 'getDetailRiwayatTransaksiStaff']) ->name('detail_riwayat_transaksi_staff');

// routes/jadwal_kerja_staff
Route::get('/jadwal_kerja_staff', [JadwalKerjaController::class, 'getJadwalKerjaStaff']) ->name('jadwal_kerja_staff');