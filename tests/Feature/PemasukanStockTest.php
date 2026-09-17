<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Services\PemasukanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PemasukanStockTest extends TestCase
{
    use RefreshDatabase;

    private function buatMenu(int $stok = 10): Menu
    {
        return Menu::create([
            'nama_menu'  => 'Nasi Goreng',
            'stok'       => $stok,
            'harga'      => 10000,
            'keterangan' => null,
        ]);
    }

    public function test_create_pemasukan_mengurangi_stok_dan_membuat_detail(): void
    {
        $menu = $this->buatMenu(10);

        $pemasukan = app(PemasukanService::class)->create([
            'tanggal'    => now()->toDateString(),
            'keterangan' => 'tes',
            'items'      => [['menu_id' => $menu->id, 'qty' => 3]],
        ]);

        $this->assertSame(7, $menu->fresh()->stok);
        $this->assertSame(30000, $pemasukan->total);
        $this->assertDatabaseHas('pemasukan_details', [
            'pemasukan_id' => $pemasukan->id,
            'menu_id'      => $menu->id,
            'qty'          => 3,
            'subtotal'     => 30000,
        ]);
    }

    public function test_create_pemasukan_gagal_dan_rollback_kalau_stok_kurang(): void
    {
        $menu = $this->buatMenu(2);

        try {
            app(PemasukanService::class)->create([
                'tanggal' => now()->toDateString(),
                'items'   => [['menu_id' => $menu->id, 'qty' => 5]],
            ]);
            $this->fail('Seharusnya melempar RuntimeException.');
        } catch (\RuntimeException $e) {
            $this->assertSame(2, $menu->fresh()->stok);
            $this->assertDatabaseCount('pemasukans', 0);
        }
    }

    public function test_delete_mengembalikan_stok_dan_restore_memotong_lagi(): void
    {
        $menu = $this->buatMenu(10);
        $service = app(PemasukanService::class);

        $pemasukan = $service->create([
            'tanggal' => now()->toDateString(),
            'items'   => [['menu_id' => $menu->id, 'qty' => 4]],
        ]);
        $this->assertSame(6, $menu->fresh()->stok);

        $service->delete($pemasukan->id);
        $this->assertSame(10, $menu->fresh()->stok);
        $this->assertSoftDeleted('pemasukans', ['id' => $pemasukan->id]);

        $service->restore($pemasukan->id);
        $this->assertSame(6, $menu->fresh()->stok);
        $this->assertNotSoftDeleted('pemasukans', ['id' => $pemasukan->id]);
    }
}
