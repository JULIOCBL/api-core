<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTypesSeeder extends Seeder
{
    protected $records = [
        [
            "id" => 1,
            "name" => "Root"
        ],
        [
            "id" => 2,
            "name" => "Super Usuario"
        ],
        [
            "id" => 3,
            "name" => "Administrator"
        ],
        [
            "id" => 4,
            "name" => "User"
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserType::upsert($this->records, ['id'], ['name']);
    }
}
