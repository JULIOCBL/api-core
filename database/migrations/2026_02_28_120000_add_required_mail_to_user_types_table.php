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
            $table->boolean('required_mail')->default(true)->after('constant');
            $table->index('required_mail');
        });

        DB::table('user_types')
            ->where('id', 4)
            ->update([
                'required_mail' => false,
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_types', function (Blueprint $table): void {
            $table->dropIndex(['required_mail']);
            $table->dropColumn('required_mail');
        });
    }
};
