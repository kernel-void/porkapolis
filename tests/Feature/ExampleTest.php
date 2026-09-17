<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_login_dapat_dibuka(): void
    {
        Setting::create([
            'nama_aplikasi' => 'Test App',
            'ikon_sidebar'  => 'fas fa-wallet',
            'tema'          => 'primary',
            'footer'        => 'Test',
        ]);

        $this->get('/')->assertStatus(200);
    }
}
