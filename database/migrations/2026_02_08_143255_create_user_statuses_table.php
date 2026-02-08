<?php

use Database\Seeders\UserStatusesSeeder;
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
        Schema::create('user_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name_es');
            $table->string('name_en');
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => UserStatusesSeeder::class,
            '--force' => true, // evitar confirmación en producción
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_statuses');
    }
};
