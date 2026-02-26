<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\JadwalKerja;
use App\Observers\JadwalKerjaObserver;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
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

                JadwalKerja::observe(JadwalKerjaObserver::class);
            }
        );
    }

    
}