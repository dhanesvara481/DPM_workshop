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
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'validasiLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'simpanData'])->name('login.attempt');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check.status'])->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return auth()->user()->role === 'admin'
            ? redirect()->route('tampilan_dashboard')
            : redirect()->route('tampilan_dashboard_staff');
    })->name('dashboard');

    // =========================================================
    // PERBAIKAN: invoice.store & invoice.check-stok dipindah ke
    // sini (auth saja, tanpa filter role) agar bisa diakses oleh
    // ADMIN dan STAFF sekaligus tanpa konflik nama route.
    // =========================================================
    Route::post('/invoice/simpan',
        [InvoiceController::class, 'simpanInvoice']
    )->name('invoice.simpan');

    Route::post('/invoice/cek-stok',
        [InvoiceController::class, 'checkStok']
    )->name('invoice.check-stok');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::group(['role' => 'admin'], function () {

        //============== Dashboard =================//
        Route::get('/tampilan_dashboard',
            [DashboardController::class, 'getTampilanDashboard']
        )->name('tampilan_dashboard');

        //============== Mengelola Barang =================//
        Route::get('/tampilan_barang',
            [BarangController::class, 'getBarang']
        )->name('mengelola_barang');

        Route::get('/tambah_barang',
            [BarangController::class, 'getTambahBarang']
        )->name('tambah_barang');

        Route::post('/simpan_barang',
            [BarangController::class, 'simpanBarang']
        )->name('simpan_barang');

        Route::get('/barang/buat_kode_barang',
            [BarangController::class, 'buatKodeBarang']
        )->name('buat_kode_barang');

        Route::get('/ubah_barang/{id}',
            [BarangController::class, 'getUbahBarang']
        )->name('ubah_barang');

        Route::post('/perbarui_barang/{id}',
            [BarangController::class, 'perbaruiBarang']
        )->name('perbarui_barang');

        Route::delete('/hapus_barang/{id}',
            [BarangController::class, 'hapusBarang']
        )->name('hapus_barang');

        //============== Barang Keluar =================//
        Route::get('/barang_keluar',
            [BarangKeluarController::class, 'getBarangKeluar']
        )->name('barang_keluar');

        Route::post('/barang_keluar/simpan',
            [BarangKeluarController::class, 'simpanBarangKeluar']
        )->name('simpan_barang_keluar');

        //============== Barang Masuk =================//
        Route::get('/barang_masuk',
            [BarangMasukController::class, 'getBarangMasuk']
        )->name('barang_masuk');

        Route::post('/barang_masuk/simpan',
            [BarangMasukController::class, 'simpanBarangMasuk']
        )->name('simpan_barang_masuk');

        //============== Stok =================//
        Route::get('/stok_realtime',
            [StokRealtimeController::class, 'getStokRealtime']
        )->name('stok_realtime');

        Route::get('/stok_realtime/print',
            [StokRealtimeController::class, 'print']
        )->name('stok_realtime.print');

        Route::get('/riwayat_perubahan_stok',
            [RiwayatPerubahanStokController::class, 'getRiwayatPerubahanStok']
        )->name('riwayat_perubahan_stok');

        //============== Transaksi =================//
        Route::get('/riwayat_transaksi',
            [RiwayatTransaksiController::class, 'getRiwayatTransaksi']
        )->name('riwayat_transaksi');

        Route::get('/detail_riwayat_transaksi/{id}',
            [RiwayatTransaksiController::class, 'getDetailRiwayatTransaksi']
        )->name('detail_riwayat_transaksi');

        Route::get('/print_transaksi/{id}',
            [RiwayatTransaksiController::class, 'nota']
        )->name('transaksi.nota');

        //============== Invoice =================//
        Route::get('/tampilan_invoice',
            [InvoiceController::class, 'getTampilanInvoice']
        )->name('tampilan_invoice');

        Route::get('/konfirmasi_invoice',
                [InvoiceController::class, 'getTampilanKonfirmasi']
            )->name('tampilan_konfirmasi_invoice');

        Route::patch('/konfirmasi_invoice/{invoice}/paid',
            [InvoiceController::class, 'tandaKonfirmasi']
        )->name('konfirmasi_invoice_tanda_konfirmasi');

        Route::delete('/konfirmasi-invoice/{id}/hapus',
            [InvoiceController::class, 'hapusKonfirmasi']
        )->name('hapus_konfirmasi_invoice');

        //============== Laporan =================//
        Route::get('/laporan_penjualan',
            [LaporanPenjualanController::class, 'getLaporanPenjualan']
        )->name('laporan_penjualan');

        Route::get('/laporan_penjualan/print',
            [LaporanPenjualanController::class, 'print']
        )->name('laporan_penjualan.print');

        //============== Jadwal Kerja =================//
        Route::get('/kelola_jadwal_kerja',
            [JadwalKerjaController::class, 'getKelolaJadwalKerja']
        )->name('kelola_jadwal_kerja');

        Route::get('/tambah_jadwal_kerja',
            [JadwalKerjaController::class, 'getTambahJadwalKerja']
        )->name('tambah_jadwal_kerja');

        Route::post('/tambah_jadwal_kerja',
            [JadwalKerjaController::class, 'simpanJadwalKerja']
        )->name('simpan_jadwal_kerja');

        Route::get('/ubah_jadwal_kerja',
            [JadwalKerjaController::class, 'getUbahJadwalKerja']
        )->name('ubah_jadwal_kerja');

        Route::put('/ubah_jadwal_kerja/{id}',
            [JadwalKerjaController::class, 'perbaruiJadwalKerja']
        )->name('perbarui_jadwal_kerja');

        Route::get('/hapus_jadwal_kerja',
            [JadwalKerjaController::class, 'getHapusJadwalKerja']
        )->name('hapus_jadwal_kerja');

        Route::delete('/hapus_jadwal_kerja/{id}',
            [JadwalKerjaController::class, 'hapusJadwalKerja']
        )->name('delete_jadwal_kerja');

        Route::delete('/hapus_jadwal_kerja_batch',
            [JadwalKerjaController::class, 'hapusBatch']
        )->name('hapus_jadwal_kerja_batch');

        Route::delete('/hapus_jadwal_kerja_all',
            [JadwalKerjaController::class, 'hapusSemuaTanggal']
        )->name('hapus_jadwal_kerja_all');

        Route::get('/tampilan_jadwal_kerja',
            [JadwalKerjaController::class, 'getTampilanJadwalKerja']
        )->name('tampilan_jadwal_kerja');

        //============== Manajemen Staf =================//
        Route::get('/tampilan_manajemen_staf',
            [ManajemenStafController::class, 'getTampilanManajemenStaf']
        )->name('tampilan_manajemen_staf');

        Route::get('/tambah_staf',
            [ManajemenStafController::class, 'getTambahStaf']
        )->name('tambah_staf');

        Route::post('/tambah_staf',
            [ManajemenStafController::class, 'simpanStaf']
        )->name('simpan_staf');

        Route::get('/ubah_staf/{id}',
            [ManajemenStafController::class, 'getUbahStaf']
        )->name('ubah_staf');

        Route::put('/ubah_staf/{id}',
            [ManajemenStafController::class, 'updateStaf']
        )->name('update_staf');

        Route::get('/detail_staf/{id}',
            [ManajemenStafController::class, 'getDetailStaf']
        )->name('detail_staf');

        Route::patch('/toggle_status_staf/{id}',
            [ManajemenStafController::class, 'toggleStatus']
        )->name('toggle_status_staf');

        //============== Notifikasi =================//
        Route::get('/tampilan_notifikasi',
            [NotifikasiController::class, 'getTampilanNotifikasi']
        )->name('tampilan_notifikasi');

        Route::get('/detail_notifikasi/{id}',
            [NotifikasiController::class, 'getDetailNotifikasi']
        )->name('detail_notifikasi');

    });

    /*
    |--------------------------------------------------------------------------
    | Staff Routes
    |--------------------------------------------------------------------------
    */
    Route::group(['role' => 'staff'], function () {

        Route::get('/tampilan_dashboard_staff',
            [DashboardController::class, 'getTampilanDashboardStaff']
        )->name('tampilan_dashboard_staff');

        Route::get('/tampilan_invoice_staff',
            [InvoiceController::class, 'getTampilanInvoiceStaff']
        )->name('tampilan_invoice_staff');

        Route::get('/staff/konfirmasi-invoice',
            [InvoiceController::class, 'getTampilanKonfirmasiStaff']
        )->name('tampilan_konfirmasi_invoice_staff');

        Route::patch('/staff/konfirmasi-invoice/{invoice}/paid',
            [InvoiceController::class, 'tandaKonfirmasiStaff']
        )->name('konfirmasi_invoice_tanda_konfirmasi_staff');

        Route::get('/stok_realtime_staff',
            [StokRealtimeController::class, 'getStokRealtimeStaff']
        )->name('stok_realtime_staff');

        Route::get('/stok_realtime_staff/print',
            [StokRealtimeController::class, 'printStaff']
        )->name('stok_realtime_staff.print');

        Route::get('/riwayat_transaksi_staff',
            [RiwayatTransaksiController::class, 'getRiwayatTransaksiStaff']
        )->name('riwayat_transaksi_staff');

        Route::get('/detail_riwayat_transaksi_staff/{id}',
            [RiwayatTransaksiController::class, 'getDetailRiwayatTransaksiStaff']
        )->name('detail_riwayat_transaksi_staff');

        Route::get('/print_transaksi_staff/{id}',
            [RiwayatTransaksiController::class, 'notaStaff']
        )->name('transaksi.nota_staff');

        Route::get('/jadwal_kerja_staff',
            [JadwalKerjaController::class, 'getJadwalKerjaStaff']
        )->name('jadwal_kerja_staff');

        Route::get('/staff/notifikasi',
            [NotifikasiController::class, 'getNotifikasiStaff']
        )->name('notifikasi_staff');

    });

});