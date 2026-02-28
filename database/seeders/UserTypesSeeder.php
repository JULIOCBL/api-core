<?php

namespace Database\Seeders;

use Src\Shared\Infrastructure\Persistence\Eloquent\Models\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTypesSeeder extends Seeder
{
    protected $records = [
        [
            "id" => 1,
            "name" => "Root",
            "constant" => "ROOT",
            "required_mail" => true,
        ],
        [
            "id" => 2,
            "name" => "Super Usuario",
            "constant" => "SUPER_USUARIO",
            "required_mail" => true,
        ],
        [
            "id" => 3,
            "name" => "Administrator",
            "constant" => "ADMINISTRATOR",
            "required_mail" => true,
        ],
        [
            "id" => 4,
            "name" => "User",
            "constant" => "USER",
            "required_mail" => false,
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserType::upsert($this->records, ['id'], ['name', 'constant', 'required_mail']);
    }
}
