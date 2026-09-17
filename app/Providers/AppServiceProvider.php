<?php

namespace App\Providers;

use App\Services\SettingService;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Singleton supaya setting hanya di-query sekali per request.
        $this->app->singleton(SettingService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id'); // Atur Carbon ke bahasa Indonesia
        setlocale(LC_TIME, 'id_ID');

        Paginator::useBootstrapFour();

        // Bagikan $pengaturan ke layout utama (dan view yang meng-include-nya).
        View::composer('layouts.master', function ($view) {
            $view->with('pengaturan', app(SettingService::class)->current());
        });
    }
}
