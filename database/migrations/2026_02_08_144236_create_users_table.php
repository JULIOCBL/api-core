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
         Schema::create('users', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->text('password');
            $table->string('username')->unique()->nullable();
            $table->integer('session_attempts_mirror')->default(3);
            $table->integer('session_attempts')->default(3);
            $table->boolean('change_password')->default(false);
            $table->boolean('is_development')->default(false);
            $table->rememberToken();
            $table->string('role_id');
            $table->unsignedBigInteger('user_status_id')->default(1);
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('restrict');
            $table->foreign('user_status_id')->references('id')->on('user_statuses')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
            $table->index('is_development');
            $table->index('deleted_at');
        });

        Schema::table('users', function (Blueprint $table) {
            DB::statement("ALTER TABLE `users` ADD `sequential` BIGINT NOT NULL AUTO_INCREMENT AFTER `id`, ADD INDEX `users_sequential_index` (`sequential`);");
        });

          DB::unprepared("
            CREATE TRIGGER before_insert_users
            BEFORE INSERT ON users
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
        Schema::dropIfExists('users');
    }
};
