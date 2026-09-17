<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_tidak_bisa_menghapus_dirinya_sendiri(): void
    {
        $user = User::factory()->create(['role_id' => '1']);
        $this->actingAs($user);

        try {
            app(UserService::class)->delete($user->id_users);
            $this->fail('Seharusnya melempar RuntimeException.');
        } catch (\RuntimeException $e) {
            $this->assertNotSoftDeleted('users', ['id_users' => $user->id_users]);
        }
    }

    public function test_user_lain_bisa_dihapus_dan_dipulihkan(): void
    {
        $aktor = User::factory()->create(['role_id' => '1']);
        $target = User::factory()->create(['role_id' => '2']);
        $this->actingAs($aktor);

        $service = app(UserService::class);

        $service->delete($target->id_users);
        $this->assertSoftDeleted('users', ['id_users' => $target->id_users]);

        $service->restore($target->id_users);
        $this->assertNotSoftDeleted('users', ['id_users' => $target->id_users]);
    }
}
