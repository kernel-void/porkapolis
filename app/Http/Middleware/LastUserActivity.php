<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LastUserActivity
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->id_users;

            $expiresAt = now()->addSeconds(60); // 1 menit cache timeout

            // Simpan ke cache
            Cache::put("user-is-online-{$userId}", true, $expiresAt);
            Cache::put("user-ip-{$userId}", $request->ip(), $expiresAt);
            Cache::put("user-agent-{$userId}", $request->userAgent(), $expiresAt);

            // Cek: update DB hanya kalau perubahan signifikan
            $shouldUpdate = false;

            if (
                $user->last_ip !== $request->ip() ||
                $user->user_agent !== $request->userAgent() ||
                now()->diffInSeconds($user->last_seen ?? now()) >= 60 // update max tiap 1 menit
            ) {
                $shouldUpdate = true;
            }

            if ($shouldUpdate) {
                $user->forceFill([
                    'last_ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'last_seen' => now(),
                ])->saveQuietly();
            }
        }

        return $next($request);
    }
}
