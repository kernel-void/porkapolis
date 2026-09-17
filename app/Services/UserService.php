<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\SettingRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class UserService
{
    // Mapping role_id (kolom lama) <-> nama role Spatie
    public const ROLE_MAP = [
        1 => 'admin',
        2 => 'kasir',
        3 => 'owner',
    ];

    public function __construct(
        private UserRepositoryInterface $users,
        private SettingRepositoryInterface $settings,
        private PdfService $pdf,
    ) {
    }

    public function all(): Collection
    {
        $users = $this->users->all()->map(fn ($user) => $this->decorate($user));

        return $users->sortBy(function ($user) {
            return [
                $user->is_online ? 0 : 1,
                $user->last_seen ? strtotime($user->last_seen) : PHP_INT_MAX,
            ];
        });
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->users->paginate($perPage)->through(fn ($user) => $this->decorate($user));
    }

    private function decorate(User $user): User
    {
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
    }

    public function allTrashed(): Collection
    {
        return $this->users->allTrashed();
    }

    public function find(string $id): User
    {
        return $this->users->find($id);
    }

    public function create(array $data): User
    {
        $user = $this->users->create([
            'id_users' => (string) Str::uuid(),
            'name'     => $data['name'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'role_id'  => $data['role_id'],
        ]);

        if (isset(self::ROLE_MAP[$data['role_id']])) {
            $this->users->syncRoles($user, self::ROLE_MAP[$data['role_id']]);
        }

        return $user;
    }

    public function update(string $id, array $data): User
    {
        $attributes = [
            'name'     => $data['name'],
            'username' => $data['username'],
            'role_id'  => $data['role_id'],
        ];

        if (!empty($data['password'])) {
            $attributes['password'] = Hash::make($data['password']);
        }

        $user = $this->users->update($id, $attributes);

        if (isset(self::ROLE_MAP[$data['role_id']])) {
            $this->users->syncRoles($user, self::ROLE_MAP[$data['role_id']]);
        }

        return $user;
    }

    public function delete(string $id): void
    {
        $user = $this->users->find($id);

        if ($user->id_users === Auth::id()) {
            throw new \RuntimeException('Anda tidak bisa menghapus akun yang sedang digunakan.');
        }

        $this->users->delete($id);
    }

    public function restore(string $id): void
    {
        $this->users->restore($id);
    }

    public function exportPdf(array $ids = []): Response
    {
        return $this->pdf->inline('admin.user.pdf', [
            'users'      => $this->users->forExport($ids),
            'pengaturan' => $this->settings->current(),
            'roleLabels' => self::ROLE_MAP,
        ], 'laporan-user-' . now()->format('Y-m-d') . '.pdf');
    }
}
