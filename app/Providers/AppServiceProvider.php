<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Barang;
use App\Models\JadwalKerja;
use App\Observers\BarangGmailObserver;
use App\Observers\JadwalKerjaGmailObserver;
use App\Observers\BarangWahaObserver;
use App\Observers\JadwalKerjaWahaObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {

            JadwalKerja::observe(JadwalKerjaWahaObserver::class);
            Barang::observe(BarangWahaObserver::class);
            JadwalKerja::observe(JadwalKerjaGmailObserver::class);
            Barang::observe(BarangGmailObserver::class);

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