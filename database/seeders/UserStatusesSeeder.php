<?php

namespace Database\Seeders;

use App\Models\UserStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserStatusesSeeder extends Seeder
{
    protected $records = [
        [
            "id" => 1,
            "name_es" => "Activo",
            "name_en" => "Active",
        ],
        [
            "id" => 2,
            "name_es" => "Inactivo",
            "name_en" => "Inactive",
        ],
        [
            "id" => 3,
            "name_es" => "Bloqueado",
            "name_en" => "Blocked",
        ],
        [
            "id" => 4,
            "name_es" => "Bloqueado temporalmente",
            "name_en" => "Temporarily blocked",
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserStatus::upsert($this->records, ['id'], ['name_es', 'name_en']);
    }
}
