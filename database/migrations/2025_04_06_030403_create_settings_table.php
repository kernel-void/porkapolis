<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->char('id_settings', 36)->primary();
            $table->string('nama_aplikasi')->default('INFAQKU');
            $table->string('ikon_sidebar')->default('fa-dollar-sign');
            $table->string('tema')->default('bg-gradient-primary');
            $table->string('footer')->default('Copyright © Pembayaran SPP');
            $table->string('logo')->nullable();
            $table->boolean('captcha_enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};