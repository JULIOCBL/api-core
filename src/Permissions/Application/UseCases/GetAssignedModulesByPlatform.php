<?php

namespace Src\Permissions\Application\UseCases;

use Src\Permissions\Application\Contracts\GetAssignedModulesByPlatformInterface;
use Src\Permissions\Domain\Contracts\PermissionInterface;
use Src\Permissions\Domain\Services\PermissionsTreeService;

/**
 * Orquesta la construcción del árbol de permisos por plataforma
 * a partir de llaves habilitadas de compañía y rol.
 */
class GetAssignedModulesByPlatform implements GetAssignedModulesByPlatformInterface
{
    /**
     * @param PermissionInterface $permission
     */
    public function __construct(protected PermissionInterface $permission)
    {
    }

    /**
     * Construye módulos asignados por plataforma para una compañía.
     *
     * @param int $company_id
     * @return array<int, array<string, mixed>>
     */
    public function buildModuleTreeByPlatform(int $company_id): array
    {
        $platform_types = $this->permission->getAccessPlatforms();
        $platform_type_ids = array_values(array_map(fn(array $platform): int => (int) $platform['id'], $platform_types));

        $permissions = $this->permission->getActiveModulePermissions($platform_type_ids);
        $keys = $this->permission->getUniqueCompanyModulePermissionKeys($company_id, $platform_type_ids);

        $data = [];

        foreach ($platform_types as $platform_type) {
            $new_permissions = [];
            $modules = [];
            $actions = [];

            foreach ($permissions as $permission) {
                if ((int) $permission['access_platform_id'] !== (int) $platform_type['id']) {
                    continue;
                }

                $new_permissions[] = [
                    'id' => $permission['id'],
                    'name' => [
                        'es' => $permission['name_es'],
                        'en' => $permission['name_en'],
                    ],
                    'type' => $permission['type'],
                    'status' => $permission['status'],
                    'key' => $permission['key'],
                    'parent_id' => $permission['parent_id'],
                    'access_platform_id' => $permission['access_platform_id'],
                    'order' => $permission['order'],
                ];
            }

            $tree = PermissionsTreeService::buildPermissionsTree($new_permissions, $modules, $actions);
            $tree = PermissionsTreeService::buildPermissionsWithDisabledKeys($tree, $keys);

            $data[] = [
                'id' => $platform_type['id'],
                'name' => [
                    'es' => $platform_type['name_es'],
                    'en' => $platform_type['name_en'],
                ],
                'assigned_modules' => $tree,
            ];
        }

        return $data;
    }

    /**
     * Construye módulos asignados por plataforma filtrando por compañía y rol.
     *
     * @param int $company_id
     * @param string $role_id
     * @param int|null $platform_type
     * @return array<int, array<string, mixed>>
     */
    public function buildFilteredModuleTreeByCompanyAndRole(int $company_id, string $role_id, ?int $platform_type = null): array
    {
        $platform_types = $this->permission->getAccessPlatforms($platform_type);
        $platform_type_ids = array_values(array_map(fn(array $platform): int => (int) $platform['id'], $platform_types));

        $permissions = $this->permission->getAllActivePermissions($platform_type_ids);
        $company_keys = $this->permission->getUniqueCompanyPermissionKeys($company_id, $platform_type_ids);
        $keys = $this->permission->getUniquePermissionKeysByRoleAndCompany($company_id, $role_id, $platform_type_ids);

        $data = [];

        foreach ($platform_types as $platform_type_item) {
            $new_permissions = [];
            $modules = [];
            $actions = [];

            foreach ($permissions as $permission) {
                if ((int) $permission['access_platform_id'] !== (int) $platform_type_item['id']) {
                    continue;
                }

                $new_permissions[] = [
                    'id' => $permission['id'],
                    'name' => [
                        'es' => $permission['name_es'],
                        'en' => $permission['name_en'],
                    ],
                    'type' => $permission['type'],
                    'status' => $permission['status'],
                    'key' => $permission['key'],
                    'parent_id' => $permission['parent_id'],
                    'access_platform_id' => $permission['access_platform_id'],
                    'order' => $permission['order'],
                ];
            }

            $tree = PermissionsTreeService::buildPermissionsTree($new_permissions, $modules, $actions);
            $tree = PermissionsTreeService::filterPermissionsTree($tree, $company_keys);
            $tree = PermissionsTreeService::buildPermissionsWithDisabledKeys($tree, $keys);

            $data[] = [
                'id' => $platform_type_item['id'],
                'name' => [
                    'es' => $platform_type_item['name_es'],
                    'en' => $platform_type_item['name_en'],
                ],
                'assigned_modules' => $tree,
            ];
        }

        return $data;
    }
}
