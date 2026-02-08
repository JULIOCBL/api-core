<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    protected $records = [
        [
            "id" => "64d882a2-6d9c-11ef-a4e2-5254004dacf7",
            "name" => "Root",
            "status" => true,
            "required_mail" => true,
            "company_id" => null,
            "user_type_id" => 1,
        ],
    ];
    public function run(): void
    {
        Role::upsert($this->records, ['id'], ['name', 'status', 'required_mail', 'company_id', 'user_type_id']);
    }
}
