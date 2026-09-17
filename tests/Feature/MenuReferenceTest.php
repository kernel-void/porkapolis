<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Pemasukan;
use App\Models\PemasukanDetail;
use App\Services\MenuService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuReferenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu_yang_dipakai_transaksi_tidak_bisa_dihapus(): void
    {
        $menu = Menu::create([
            'nama_menu' => 'Es Teh',
            'stok'      => 10,
            'harga'     => 5000,
        ]);

        $pemasukan = Pemasukan::create([
            'tanggal'    => now()->toDateString(),
            'total'      => 5000,
            'keterangan' => null,
        ]);

        PemasukanDetail::create([
            'pemasukan_id' => $pemasukan->id,
            'menu_id'      => $menu->id,
            'qty'          => 1,
            'subtotal'     => 5000,
        ]);

        try {
            app(MenuService::class)->delete($menu->id);
            $this->fail('Seharusnya melempar RuntimeException.');
        } catch (\RuntimeException $e) {
            $this->assertNotSoftDeleted('menus', ['id' => $menu->id]);
        }
    }

    public function test_menu_tanpa_referensi_bisa_dihapus_dan_dipulihkan(): void
    {
        $menu = Menu::create([
            'nama_menu' => 'Kopi',
            'stok'      => 5,
            'harga'     => 8000,
        ]);

        $service = app(MenuService::class);

        $service->delete($menu->id);
        $this->assertSoftDeleted('menus', ['id' => $menu->id]);

        $service->restore($menu->id);
        $this->assertNotSoftDeleted('menus', ['id' => $menu->id]);
    }
}
