<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\RiwayatPerubahanStokController;
use App\Http\Controllers\RiwayatTransaksiController;

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