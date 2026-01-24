<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BarangController;

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

