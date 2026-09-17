<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\ChangePasswordRequest;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct(private ProfileService $profileService)
    {
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $this->profileService->changePassword(Auth::user(), $request->validated());

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
