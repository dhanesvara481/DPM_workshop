<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Barang;
use App\Models\JadwalKerja;
use App\Observers\BarangObserver;
use App\Observers\JadwalKerjaObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // ✅ Daftarkan observer di sini — BUKAN di dalam View Composer
        // agar tidak terdaftar berulang setiap kali view di-render
        JadwalKerja::observe(JadwalKerjaObserver::class);
        Barang::observe(BarangObserver::class);

        View::composer(
            [
                'staff.sidebar.sidebar',
                'admin.sidebar',
                'staff.layout.app',
                'admin.layout.app',
            ],
            function (\Illuminate\View\View $view) {
                $user = Auth::user();

                $view->with([
                    'username' => $user?->username ?? 'User',
                    'role'     => $user?->role     ?? 'Staff',
                ]);
            }
        );
    }
}