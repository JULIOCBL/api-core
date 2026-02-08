<?php

use Database\Seeders\RolesSeeder;
use Database\Seeders\UserTypesSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('user_type_id');
            $table->string('name');
            $table->boolean('required_mail')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index('deleted_at');
            $table->index('status');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('restrict');
            $table->foreign('user_type_id')->references('id')->on('user_types')->onDelete('restrict');
        });

        Schema::table('roles', function (Blueprint $table) {
            DB::statement("ALTER TABLE `roles` ADD `sequential` BIGINT NOT NULL AUTO_INCREMENT AFTER `id`, ADD INDEX `roles_sequential_index` (`sequential`);");
        });

        DB::unprepared("
            CREATE TRIGGER before_insert_roles
            BEFORE INSERT ON roles
            FOR EACH ROW
            BEGIN
                IF NEW.id IS NULL OR NEW.id = '' THEN
                    SET NEW.id = UUID();
                END IF;
            END;
        ");

        Artisan::call('db:seed', [
            '--class' => UserTypesSeeder::class,
            '--force' => true, // evitar confirmación en producción
        ]);
        Artisan::call('db:seed', [
            '--class' => RolesSeeder::class,
            '--force' => true, // evitar confirmación en producción
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
