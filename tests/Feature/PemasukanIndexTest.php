<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Pemasukan;
use App\Models\PemasukanDetail;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PemasukanIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_tabel_pemasukan_menampilkan_tombol_dan_modal_rincian(): void
    {
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        Setting::create([
            'nama_aplikasi' => 'Test App',
            'ikon_sidebar'  => 'fas fa-wallet',
            'tema'          => 'primary',
            'footer'        => 'Test',
        ]);

        $user = User::factory()->create(['role_id' => '1']);
        $user->syncRoles('admin');

        $menu = Menu::create(['nama_menu' => 'Nasi Goreng', 'stok' => 10, 'harga' => 10000]);

        $pemasukan = Pemasukan::create([
            'tanggal'    => now()->toDateString(),
            'total'      => 30000,
            'keterangan' => null,
        ]);

        PemasukanDetail::create([
            'pemasukan_id' => $pemasukan->id,
            'menu_id'      => $menu->id,
            'qty'          => 3,
            'subtotal'     => 30000,
        ]);

        $this->actingAs($user)
            ->get('/pemasukan')
            ->assertStatus(200)
            ->assertSee('rincianBtn', false)
            ->assertSee('rincianModal', false);
    }
}
