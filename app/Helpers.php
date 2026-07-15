<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('getDashboardRoute')) {
    function getDashboardRoute()
    {
        if (!Auth::check()) {
            return route('auth'); // Redirect ke login jika belum login
        }

        $user = Auth::user();

        switch ($user->role_id) {
            case 1:
                return route('admin.dashboard');
            case 2:
                return route('kasir.dashboard');
            case 3:
                return route('owner.dashboard');
            default:
                return route('auth'); // Default jika role_id tidak dikenali
        }
    }
}
