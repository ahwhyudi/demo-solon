<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notifikasi;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // URL::forceScheme('https');

        View::composer('*', function ($view) {

            if (auth()->check()) {

                $notifikasiNavbar = Notifikasi::with([
                    'jobDivisi',
                    'formOrder'
                ])
                    ->where('user_id', auth()->id())
                    ->latest()
                    ->take(10)
                    ->get();

                $totalNotifNavbar = Notifikasi::where('user_id', auth()->id())
                    ->where('is_read', 0)
                    ->count();

                $view->with([
                    'notifikasiNavbar' => $notifikasiNavbar,
                    'totalNotifNavbar' => $totalNotifNavbar
                ]);
            }
        });
    }
}
