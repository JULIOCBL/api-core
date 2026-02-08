<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name_es');
            $table->string('name_en');
            $table->enum('type', ['MODULE', 'ACTION']);
            $table->boolean('status')->default(true);
            $table->char("key", 255)->unique()->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('access_platform_id')->nullable();
            $table->bigInteger('order');
            $table->foreign('access_platform_id')->references('id')->on('access_platforms')->onDelete('restrict');
            $table->foreign('parent_id')->references('id')->on('permissions')->onDelete('restrict');
            $table->timestamps();
            $table->index('type');
            $table->index('status');
            $table->index('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
