<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_types', function (Blueprint $table): void {
            $table->string('constant', 100)->nullable()->after('name');
        });

        DB::table('user_types')
            ->whereNull('constant')
            ->orWhere('constant', '')
            ->update([
                'constant' => DB::raw('name')
            ]);

        Schema::table('user_types', function (Blueprint $table): void {
            $table->string('constant', 100)->nullable(false)->change();
            $table->index('constant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_types', function (Blueprint $table): void {
            $table->dropIndex(['constant']);
            $table->dropColumn('constant');
        });
    }
};
