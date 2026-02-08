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
        Schema::create('refresh_access_tokens', function (Blueprint $table) {
            $table->bigIncrements('id'); // Crea un ID autoincremental como clave primaria
            $table->unsignedBigInteger('personal_access_token_id');
            $table->text('token');
            $table->timestamp('expires_at')->nullable(); // Fecha de expiración del token de refresco
            $table->timestamps(); // Crea las columnas created_at y updated_at
            // Asegúrate de ajustar las siguientes líneas según tus necesidades específicas
            $table->foreign('personal_access_token_id')->references('id')->on('personal_access_tokens')
                ->onDelete('cascade'); // Ajusta la restricción según sea necesario
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refresh_access_tokens');
    }
};
