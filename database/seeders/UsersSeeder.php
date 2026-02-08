<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;

class UsersSeeder extends Seeder
{
     /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (config('app.debug')) {
            User::firstOrCreate(
                ["id" => "64d882a2-6d9c-11ef-a4e2-5254004dacf7"],
                [
                    "name" => "Julio Cesar",
                    "last_name" => "Borges Lopez",
                    "email" => "desarrollador.julio.borges@gmail.com",
                    "username" => "julio.borges",
                    "password" => Crypt::encrypt('gf' . date('Y')),
                    "session_attempts" => 10,
                    "session_attempts_mirror" => 10,
                    "change_password" => false,
                    "is_development" => true,
                    "role_id" => "64d882a2-6d9c-11ef-a4e2-5254004dacf7",
                    "user_status_id" => 1,
                ]
            );

            User::query()->update(['password' => Crypt::encrypt('gst' . date('Y'))]);
        } else {

            User::firstOrCreate(
                ["id" => "64d882a2-6d9c-11ef-a4e2-5254004dacf7"],
                [
                    "name" => "Julio Cesar",
                    "last_name" => "Borges Lopez",
                    "email" => "desarrollador.julio.borges@gmail.com",
                    "username" => "julio.borges",
                    "password" => Crypt::encrypt('gf' . date('Y')),
                    "session_attempts" => 10,
                    "session_attempts_mirror" => 10,
                    "change_password" => false,
                    "is_development" => true,
                    "role_id" => "64d882a2-6d9c-11ef-a4e2-5254004dacf7",
                    "user_status_id" => 1,
                ]
            );
        }
    }
}
