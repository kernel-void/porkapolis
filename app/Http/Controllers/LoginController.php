<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
        ]);

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

        $user = User::where('username', $credentials['username'])->first();

        // Pesan error disamakan (tidak membedakan "username tidak ada" vs "password salah")
        // supaya penyerang tidak bisa menebak username mana yang valid.
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            RateLimiter::hit($throttleKey, 60); // kunci 60 detik per hit dalam window

            Log::warning('Percobaan login gagal', [
                'username' => $credentials['username'],
                'ip'       => $request->ip(),
            ]);

            return back()
                ->withErrors(['username' => 'Username atau password salah.'])
                ->withInput($request->only('username'));
        }

        // Cek role valid SEBELUM login benar-benar dijalankan
        if (!in_array($user->role_id, [1, 2, 3])) {
            Log::warning('Login ditolak: role tidak dikenali', [
                'username' => $user->username,
                'role_id'  => $user->role_id,
            ]);

            return back()
                ->withErrors(['username' => 'Akun ini tidak memiliki akses yang valid.'])
                ->withInput($request->only('username'));
        }

        RateLimiter::clear($throttleKey);

        Auth::login($user);

        // Cegah session fixation: regenerasi session ID setelah login berhasil
        $request->session()->regenerate();

        $user->login_times = now();
        $user->last_ip = $request->ip();
        $user->user_agent = $request->userAgent();
        $user->save();

        session(['id_users' => $user->id_users]);

        Log::info('User login berhasil', [
            'username' => $user->username,
            'role_id'  => $user->role_id,
            'ip'       => $request->ip(),
        ]);

        $roleLabels = [1 => 'Admin', 2 => 'Kasir', 3 => 'Owner'];
        session()->flash('success', 'Login berhasil! Selamat datang, ' . $roleLabels[$user->role_id] . '.');

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            $userId = Auth::user()->id_users;

            Cache::forget("user-is-online-{$userId}");
            Cache::forget("user-ip-{$userId}");
            Cache::forget("user-agent-{$userId}");

            Log::info('User logout', ['username' => Auth::user()->username]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth');
    }
}