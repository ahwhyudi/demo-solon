<?php

namespace App\Providers;

use App\Models\Bank;
use App\Models\Broker;
use App\Models\BrokerMarketing;
use App\Models\Developer;
use App\Models\DeveloperLegal;
use App\Models\DeveloperMarketing;
use App\Models\Notifikasi;
use App\Models\Setting;
use App\Observers\BankObserver;
use App\Observers\BrokerMarketingObserver;
use App\Observers\BrokerObserver;
use App\Observers\DeveloperLegalObserver;
use App\Observers\DeveloperMarketingObserver;
use App\Observers\DeveloperObserver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Bank::observe(BankObserver::class);
        Developer::observe(DeveloperObserver::class);
        Broker::observe(BrokerObserver::class);
        DeveloperMarketing::observe(DeveloperMarketingObserver::class);
        DeveloperLegal::observe(DeveloperLegalObserver::class);
        BrokerMarketing::observe(BrokerMarketingObserver::class);

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
