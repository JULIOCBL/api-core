<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            UPDATE personal_access_tokens
            SET token = SHA2(token, 256)
            WHERE token IS NOT NULL
              AND token <> ''
              AND token NOT REGEXP '^[A-Fa-f0-9]{64}$'
        ");

        DB::statement("
            UPDATE refresh_access_tokens
            SET token = SHA2(token, 256)
            WHERE token IS NOT NULL
              AND token <> ''
              AND token NOT REGEXP '^[A-Fa-f0-9]{64}$'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
