<?php

namespace Tests\Feature;

use App\Models\Pemasukan;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private function seedSetting(): void
    {
        Setting::create([
            'nama_aplikasi' => 'Test App',
            'ikon_sidebar'  => 'fas fa-wallet',
            'tema'          => 'primary',
            'footer'        => 'Test',
        ]);
    }

    public function test_dashboard_dapat_dirender_dengan_data_chart(): void
    {
        $this->seedSetting();

        $user = User::factory()->create(['role_id' => '1']);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertStatus(200)
            ->assertSee('data-labels=', false)
            ->assertSee('myBarChart', false);
    }

    public function test_filter_bulan_menampilkan_bulan_terpilih(): void
    {
        $this->seedSetting();

        $user = User::factory()->create(['role_id' => '1']);

        Pemasukan::create([
            'tanggal'    => '2024-05-01',
            'total'      => 100000,
            'keterangan' => null,
        ]);

        $this->actingAs($user)
            ->post(route('admin.dashboard.bulan'), ['bulan' => '2024-05'])
            ->assertRedirect(route('admin.dashboard'));

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertStatus(200)
            ->assertSee('name="bulan"', false)
            ->assertSee('value="2024-05"', false);
    }
}
