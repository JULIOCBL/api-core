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
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->char('key', 255)->nullable();
            $table->string('role_id');
            $table->boolean('status')->default(true);
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('restrict');
            $table->timestamps();
            $table->index('key');
            $table->index('status');
            $table->softDeletes();
            $table->index('deleted_at');
        });

        Schema::table('role_permissions', function (Blueprint $table) {
            DB::statement("ALTER TABLE `role_permissions` ADD `sequential` BIGINT NOT NULL AUTO_INCREMENT AFTER `id`, ADD INDEX `role_permissions_sequential_index` (`sequential`);");
        });

        DB::unprepared("
            CREATE TRIGGER before_insert_role_permissions
            BEFORE INSERT ON role_permissions
            FOR EACH ROW
            BEGIN
                IF NEW.id IS NULL OR NEW.id = '' THEN
                    SET NEW.id = UUID();
                END IF;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
