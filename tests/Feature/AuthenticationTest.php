<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_diarahkan_ke_halaman_auth(): void
    {
        $this->get('/dashboard')->assertRedirect(route('auth'));
    }

    public function test_login_berhasil_diarahkan_ke_dashboard(): void
    {
        $user = User::factory()->create([
            'username' => 'tester',
            'password' => Hash::make('secret123'),
            'role_id'  => '1',
        ]);

        $this->post('/login', [
            'username' => 'tester',
            'password' => 'secret123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_gagal_tidak_mengautentikasi(): void
    {
        User::factory()->create([
            'username' => 'tester',
            'password' => Hash::make('secret123'),
            'role_id'  => '1',
        ]);

        $this->post('/login', [
            'username' => 'tester',
            'password' => 'salah',
        ])->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_guest_tidak_bisa_ganti_password(): void
    {
        $this->post('/profile/change-password', [
            'password_lama'          => 'x',
            'password_baru'          => 'y12345',
            'password_baru_confirmation' => 'y12345',
        ])->assertRedirect(route('auth'));

        $this->assertGuest();
    }

    public function test_logout_mengakhiri_sesi(): void
    {
        $user = User::factory()->create(['role_id' => '1']);

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect(route('auth'));

        $this->assertGuest();
    }
}
