<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function authenticate(LoginRequest $request)
    {
        $user = $this->authService->attempt($request->validated(), $request);

        Auth::login($user);

        // Cegah session fixation: regenerasi session ID setelah login berhasil
        $request->session()->regenerate();

        session(['id_users' => $user->id_users]);

        $roleLabels = array_map('ucfirst', UserService::ROLE_MAP);
        session()->flash('success', 'Login berhasil! Selamat datang, ' . ($roleLabels[$user->role_id] ?? 'User') . '.');

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            $this->authService->logout(Auth::user());
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth');
    }
}
