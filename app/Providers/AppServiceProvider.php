<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Tagihan;


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
        Carbon::setLocale('id'); // Atur Carbon ke bahasa Indonesia
        setlocale(LC_TIME, 'id_ID');

        View::composer('components.topbar-siswa', function ($view) {
            $user = Auth::user();
    
            $tagihan = collect();
            $tagihanCount = 0;
    
            // Jika user login dan role_id adalah siswa (2)
            if ($user && $user->role_id == 2) {
                $siswa = Siswa::where('users_id', $user->id_users)->first();
    
                if ($siswa) {
                    $tagihan = Tagihan::where('id_siswa', $siswa->id_siswa)
                        ->where('status', 'BELUM DIBAYAR')
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
    
                    $tagihanCount = $tagihan->count();
                }
            }
    
            $view->with([
                'tagihan' => $tagihan,
                'tagihanCount' => $tagihanCount
            ]);
        });
    }
}
