<?php

namespace Database\Seeders;

use App\Models\AccessPlatform;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccessPlatformsSeeder extends Seeder
{
    protected $records = [
        [
            "id" => 1,
            "name_es" => "Aplicación Web",
            "name_en" => "Application Web",
        ],
        [
            "id" => 2,
            "name_es" => "Aplicación Móvil",
            "name_en" => "Application Mobile",
        ],
        [
            "id" => 3,
            "name_es" => "Aplicación Escritorio",
            "name_en" => "Application Desktop",
        ],
        [
            "id" => 4,
            "name_es" => "Api de Integración",
            "name_en" => "Integration API",
        ],
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AccessPlatform::upsert($this->records, ['id'], ['name_es', 'name_en']);
    }
}
