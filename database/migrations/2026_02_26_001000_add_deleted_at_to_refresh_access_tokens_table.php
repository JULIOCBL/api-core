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
        Schema::table('refresh_access_tokens', function (Blueprint $table): void {
            if (!Schema::hasColumn('refresh_access_tokens', 'deleted_at')) {
                $table->softDeletes();
                $table->index('deleted_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refresh_access_tokens', function (Blueprint $table): void {
            if (Schema::hasColumn('refresh_access_tokens', 'deleted_at')) {
                $table->dropIndex(['deleted_at']);
                $table->dropSoftDeletes();
            }
        });
    }
};
