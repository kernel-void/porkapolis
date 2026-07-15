<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->char('id_users', 36)->primary();
            $table->string('username')->unique();
            $table->string('name');
            $table->string('password');
            $table->string('bypass')->nullable();
            $table->string('gambar')->nullable();
            $table->enum('role_id', ['1', '2', '3', '4', '5']);
            $table->timestamp('login_times')->nullable();
            $table->string('last_ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};