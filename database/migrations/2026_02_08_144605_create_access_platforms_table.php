<?php

use Database\Seeders\AccessPlatformsSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('access_platforms', function (Blueprint $table) {
            $table->id();
            $table->string('name_es');
            $table->string('name_en');
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => AccessPlatformsSeeder::class,
            '--force' => true, // evitar confirmación en producción
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_platforms');
    }
};
