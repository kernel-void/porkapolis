<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\SettingService;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService,
        private SettingService $settingService,
    ) {
    }

    public function index()
    {
        return view('admin.user.index', [
            'pengaturan' => $this->settingService->current(),
            'users'      => $this->userService->paginate(),
            'trashed'    => $this->userService->allTrashed(),
            'roleLabels' => UserService::ROLE_MAP,
        ]);
    }

    public function edit($id)
    {
        return view('admin.user.edit', [
            'user'       => $this->userService->find($id),
            'pengaturan' => $this->settingService->current(),
        ]);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $this->userService->update($id, $request->validated());

        return redirect()->route('admin.user.index')->with('success', 'User berhasil diperbarui!');
    }

    public function store(StoreUserRequest $request)
    {
        $this->userService->create($request->validated());

        return redirect()->route('admin.user.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        try {
            $this->userService->delete($id);
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'User berhasil dihapus. Masih bisa dipulihkan dari Data Terhapus.');
    }

    public function restore($id)
    {
        $this->userService->restore($id);

        return redirect()->back()->with('success', 'User berhasil dipulihkan.');
    }

    public function exportPdf(Request $request)
    {
        return $this->userService->exportPdf($request->input('user_ids', []));
    }
}
