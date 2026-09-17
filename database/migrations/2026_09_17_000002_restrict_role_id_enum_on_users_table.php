<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Amankan data lama yang di luar 1-3 sebelum enum dipersempit
        DB::table('users')->whereNotIn('role_id', ['1', '2', '3'])->update(['role_id' => '1']);

        DB::statement("ALTER TABLE users MODIFY role_id ENUM('1','2','3') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY role_id ENUM('1','2','3','4','5') NOT NULL");
    }
};
