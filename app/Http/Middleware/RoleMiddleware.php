<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('auth');
        }

        $user = Auth::user();
        $role_id = $user->role_id;

        // Mapping role ke role_id di database
        $allowedRoles = [
            'admin'     => 1,
            'kasir'     => 2,
            'owner'     => 3
        ];

        // Jika role tidak sesuai, arahkan ke halaman blank
        if (!isset($allowedRoles[$role]) || $role_id != $allowedRoles[$role]) {
            return response()->view('blank'); // Menampilkan halaman blank.blade.php
        }

        return $next($request);
    }
}
