<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Agent\Agent;
use Carbon\Carbon;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Mapping role_id (kolom lama) <-> nama role Spatie
    protected array $roleMap = [
        1 => 'admin',
        2 => 'kasir',
        3 => 'owner',
    ];

    public function index()
    {
        $pengaturan = Setting::first();
        $users = User::all();

        $users = $users->map(function ($user) {
            $user->is_online   = Cache::has("user-is-online-{$user->id_users}");
            $user->online_ip   = Cache::get("user-ip-{$user->id_users}", $user->last_ip);
            $user->user_agent  = Cache::get("user-agent-{$user->id_users}", $user->user_agent);

            $user->last_seen_text = $user->last_seen
                ? Carbon::parse($user->last_seen)->diffForHumans()
                : ' - ';

            if (!empty($user->user_agent)) {
                $agent = new Agent();
                $agent->setUserAgent($user->user_agent);

                if ($agent->isMobile() || $agent->isTablet()) {
                    $user->device_info = $agent->device() . ' - ' . $agent->browser();
                } else {
                    $user->device_info = $agent->platform() . ' - ' . $agent->browser();
                }
            } else {
                $user->device_info = ' - ';
            }

            return $user;
        });

        $users = $users->sortBy(function ($user) {
            return [
                $user->is_online ? 0 : 1,
                $user->last_seen ? strtotime($user->last_seen) : PHP_INT_MAX,
            ];
        });

        return view('admin.user.index', compact('users', 'pengaturan'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $pengaturan = Setting::first();
        $roles = DB::table('users')->select('role_id')->distinct()->get();

        return view('admin.user.edit', compact('user', 'roles', 'pengaturan'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id . ',id_users',
            'password' => 'nullable|min:5',
            'role_id'  => 'required|in:1,2,3,4,5',
        ], [
            'name.required'     => 'Nama tidak boleh kosong.',
            'name.string'       => 'Nama harus berupa teks.',
            'name.max'          => 'Nama maksimal 255 karakter.',
            'username.required' => 'Username tidak boleh kosong.',
            'username.string'   => 'Username harus berupa teks.',
            'username.max'      => 'Username maksimal 255 karakter.',
            'username.unique'   => 'Username sudah digunakan, silakan gunakan yang lain.',
            'password.min'      => 'Password minimal 5 karakter.',
            'role_id.required'  => 'Role wajib dipilih.',
            'role_id.in'        => 'Role yang dipilih tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->username = $request->username;
        $user->role_id = $request->role_id;

        if ($request->password) {
            $user->password = Hash::make($request->password);
            $user->bypass = $request->password; // Simpan password asli di bypass
        }
        $user->save();

        // Sinkronkan role Spatie mengikuti role_id yang dipilih
        if (isset($this->roleMap[$request->role_id])) {
            $user->syncRoles($this->roleMap[$request->role_id]);
        }

        return redirect()->route('admin.user.index')->with('success', 'User berhasil diperbarui!');
    }
}