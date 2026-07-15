<?php

namespace App\Http\Controllers;


use App\Models\Guru;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        // Validasi input login (username & password wajib diisi)
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
        ]);

        // Ambil user berdasarkan username
        $user = User::where('username', $credentials['username'])->first();

        // Cek apakah user ditemukan
        if (!$user) {
            return back()->withErrors(['username' => 'Username tidak ditemukan'])->withInput();
        }

        // Cek apakah password cocok
        if (!Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['password' => 'Password salah'])->withInput();
        }

        // Login user
        Auth::login($user);

        // Cek apakah ini login pertama atau bukan (sebelum update)
        if (is_null($user->login_times)) {
            session()->flash('success', 'Login berhasil! Selamat datang.');
        } else {
            session()->flash('success', 'Login berhasil! Selamat datang.');
        }

        // Update login_times ke waktu sekarang
        $user->login_times = now();
        $user->save();

        // Ambil role_id dan id_users
        $role_id = $user->role_id ?? null;
        $userId = $user->id_users ?? null;

        // Jika id_users tidak ditemukan
        if (!$userId) {
            Log::error('ID User tidak ditemukan saat login', ['user' => $user]);
            return redirect()->route('auth')->withErrors(['error' => 'Terjadi kesalahan saat login, coba lagi.']);
        }

        // Simpan id_users ke session
        session(['id_users' => $userId]);

        // Log aktivitas login
        Log::info("User login: {$user->username} dengan role {$role_id}", ['id_users' => $userId]);

        $redirectRoute = null;
        $userData = null;

        // Arahkan berdasarkan role_id
        switch ($user->role_id) {
            case 1: // Admin
                session()->flash('success', 'Login berhasil! Selamat datang, Admin.');
                return redirect()->route('admin.dashboard');
                break;

            case 2: // Kasir
                session()->flash('success', 'Login berhasil! Selamat datang, Kasir.');
                return redirect()->route('kasir.dashboard');
                break;

            case 3: // Owner
                session()->flash('success', 'Login berhasil! Selamat datang, Owner.');
                return redirect()->route('owner.dashboard');
                break;

            default:
                Auth::logout();
                return redirect()->route('auth')->withErrors(['error' => 'Role tidak dikenali.']);
        }

        session()->put('userData', [
            'id' => $userData->id,
            'nama' => $userData->nama ?? $userData->name,
            'role' => $role_id,
        ]);

        return redirect()->route($redirectRoute);
    }

    public function authenticated(Request $request, $user)
    {
        Session::put('login_times', now());

        return redirect()->intended('dashboard');
    }

    /**
     * Fungsi untuk menentukan route berdasarkan role_id
     */
    private function getRoleRoute($role_id)
    {
        $roles = [
            1 => 'admin',
            2 => 'kasir',
            3 => 'owner'
        ];
        return $roles[$role_id] ?? 'login';
    }

    /**
     * Fungsi logout
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            $userId = Auth::user()->id_users;

            // Hapus cache status online
            Cache::forget("user-is-online-{$userId}");
            Cache::forget("user-ip-{$userId}");
            Cache::forget("user-agent-{$userId}");
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth'); // arahkan ke halaman login kamu
    }

}
