<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(UserTypesSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(UserStatusesSeeder::class);
        $this->call(AccessPlatformsSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(CompaniesSeeder::class);
        $this->call(UsersSeeder::class);
    }
}
