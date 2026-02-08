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
           Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->ipAddress();
            $table->decimal('latitude', 10, 7)->nullable();  // hasta 7 decimales de precisión
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('tokenable_type');
            $table->string('user_id');
            $table->integer('platform_type');
            $table->string('name_platform_type');
            $table->string('device_type');
            $table->longText('token')->unique();
            $table->longText('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->datetime('created_at_utc')->nullable();
            $table->datetime('updated_at_utc')->nullable();
            $table->softDeletes();
            $table->index('user_id');
            $table->index('abilities');
            $table->index('created_at_utc');
            $table->index('updated_at_utc');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
