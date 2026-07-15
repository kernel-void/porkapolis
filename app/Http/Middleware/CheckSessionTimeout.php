<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CheckSessionTimeout
{
    public function handle(Request $request, Closure $next)
    {
        if (Session::has('login_time')) {
            $loginTime = Session::get('login_time');
            $timeout = now()->diffInMinutes($loginTime);

            if ($timeout >= 180) {
                Auth::logout();
                Session::flush();
                return redirect()->route('auth')->with('message', 'Sesi anda telah berakhir. Silakan login kembali.');
            }
        }

        return $next($request);
    }
}

