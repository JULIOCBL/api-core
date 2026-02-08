<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Src\Core\Infrastructure\Support\Utils\CacheWithIndex;

class PermissionsSeeder extends Seeder
{
    // KEY_<PLATAFORMA>_<MODULO>_<TIPO>_<ACCION>_<OBJETO>

    protected $records = [
        [
            "id" => 1,
            "name_es" => "Roles",
            "name_en" => "Roles",
            "type" => "MODULE",
            "status" => true,
            "key" => "KEY_WEB_ROLES_MODULE",
            "parent_id" => null,
            "access_platform_id" => 1,
            "order" => 1
        ],
        [
            "id" => 2,
            "name_es" => "Ver lista de roles",
            "name_en" => "Ver lista de roles",
            "type" => "ACTION",
            "status" => true,
            "key" => "KEY_WEB_ROLES_ACTION_VIEW_ROLE",
            "parent_id" => 1,
            "access_platform_id" => 1,
            "order" => 1
        ],
        [
            "id" => 3,
            "name_es" => "Crear role",
            "name_en" => "Crear role",
            "type" => "ACTION",
            "status" => true,
            "key" => "KEY_WEB_ROLES_ACTION_CREATE_ROLE",
            "parent_id" => 1,
            "access_platform_id" => 1,
            "order" => 2
        ],
        [
            "id" => 4,
            "name_es" => "Permisos",
            "name_en" => "Permissions",
            "type" => "MODULE",
            "status" => true,
            "key" => "KEY_WEB_PERMISSIONS_MODULE",
            "parent_id" => 1,
            "access_platform_id" => 1,
            "order" => 1
        ],
        [
            "id" => 5,
            "name_es" => "Update permissions",
            "name_en" => "Update permissions",
            "type" => "ACTION",
            "status" => true,
            "key" => "KEY_WEB_PERMISSIONS_ACTION_UPDATE_PERMISSIONS",
            "parent_id" => 4,
            "access_platform_id" => 1,
            "order" => 1
        ],
        [
            "id" => 6,
            "name_es" => "Users",
            "name_en" => "Users",
            "type" => "MODULE",
            "status" => true,
            "key" => "KEY_WEB_USERS_MODULE",
            "parent_id" => null,
            "access_platform_id" => 1,
            "order" => 1
        ],
        [
            "id" => 7,
            "name_es" => "Ver lista de usuarios",
            "name_en" => "Ver lista de usuarios",
            "type" => "ACTION",
            "status" => true,
            "key" => "KEY_WEB_USERS_ACTION_VIEW_LIST_OF_USERS",
            "parent_id" => 6,
            "access_platform_id" => 1,
            "order" => 1
        ],
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::upsert($this->records, ['id'], ['name_es', 'name_en', 'type', 'status', 'key', 'parent_id', 'access_platform_id', 'access_platform_id']);

        $cache = new CacheWithIndex('cache_permissions');
        $cache->clear();

    }
}
