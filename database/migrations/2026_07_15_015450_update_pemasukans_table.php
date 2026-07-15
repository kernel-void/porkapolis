<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemasukans', function (Blueprint $table) {
            $table->dropForeign(['menu_id']);
        });

        Schema::table('pemasukans', function (Blueprint $table) {
            $table->dropColumn(['menu_id', 'qty']);
        });

        Schema::create('pemasukan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemasukan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_id')->constrained();
            $table->unsignedInteger('qty');
            $table->unsignedBigInteger('subtotal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemasukan_details');

        Schema::table('pemasukans', function (Blueprint $table) {
            $table->foreignId('menu_id')->after('id')->constrained();
            $table->unsignedInteger('qty')->after('menu_id');
        });
    }
};