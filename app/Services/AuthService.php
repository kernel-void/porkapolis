<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(private UserRepositoryInterface $users)
    {
    }

    /**
     * Verifikasi kredensial login (rate limit + role check) dan catat aktivitas login.
     *
     * @throws ValidationException
     */
    public function attempt(array $credentials, Request $request): User
    {
        // Rate limit per kombinasi username + IP, supaya brute-force ke 1 akun
        // dari banyak IP maupun banyak akun dari 1 IP tetap terblokir.
        $throttleKey = strtolower($credentials['username']) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            Log::warning('Login diblokir karena terlalu banyak percobaan', [
                'username' => $credentials['username'],
                'ip'       => $request->ip(),
            ]);

            throw ValidationException::withMessages([
                'username' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        $user = $this->users->findByUsername($credentials['username']);

        // Pesan error disamakan (tidak membedakan "username tidak ada" vs "password salah")
        // supaya penyerang tidak bisa menebak username mana yang valid.
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            RateLimiter::hit($throttleKey, 60);

            Log::warning('Percobaan login gagal', [
                'username' => $credentials['username'],
                'ip'       => $request->ip(),
            ]);

            throw ValidationException::withMessages([
                'username' => 'Username atau password salah.',
            ]);
        }

        // Cek role valid SEBELUM login benar-benar dijalankan
        if (!in_array($user->role_id, [1, 2, 3])) {
            Log::warning('Login ditolak: role tidak dikenali', [
                'username' => $user->username,
                'role_id'  => $user->role_id,
            ]);

            throw ValidationException::withMessages([
                'username' => 'Akun ini tidak memiliki akses yang valid.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        $user->login_times = now();
        $user->last_ip = $request->ip();
        $user->user_agent = $request->userAgent();
        $user->save();

        Log::info('User login berhasil', [
            'username' => $user->username,
            'role_id'  => $user->role_id,
            'ip'       => $request->ip(),
        ]);

        return $user;
    }

    public function logout(User $user): void
    {
        $userId = $user->id_users;

        Cache::forget("user-is-online-{$userId}");
        Cache::forget("user-ip-{$userId}");
        Cache::forget("user-agent-{$userId}");

        Log::info('User logout', ['username' => $user->username]);
    }
}
