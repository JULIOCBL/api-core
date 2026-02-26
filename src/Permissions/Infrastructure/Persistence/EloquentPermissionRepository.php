<?php

namespace Src\Permissions\Infrastructure\Persistence;


use Illuminate\Database\Eloquent\Builder;
use Src\Core\Infrastructure\Support\Utils\CacheWithIndex;
use Src\Permissions\Domain\Contracts\PermissionInterface;
use Src\Shared\Infrastructure\Persistence\Eloquent\Models\AccessPlatform;
use Src\Shared\Infrastructure\Persistence\Eloquent\Models\Permission;

/**
 * Implementación de infraestructura para consultas de permisos
 * usando Query Builder de Laravel y caché indexada.
 */
class EloquentPermissionRepository implements PermissionInterface
{
      /**
     * Retorna una nueva instancia del query builder base para permisos activos.
     */
    public function newQuery(): Builder
    {

        return Permission::query()->select(
            "permissions.id",
            "permissions.name_es",
            "permissions.name_en",
            "permissions.type",
            "permissions.status",
            "permissions.key",
            "permissions.parent_id",
            "permissions.access_platform_id",
            "permissions.order",
        )->where("permissions.status", 1);
    }

    /**
     * Obtiene todos los permisos activos de tipo MODULE.
     *
     * @return array
     */
    public function getActiveModulePermissions(array $platform_type_ids): array
    {
        $cache = new CacheWithIndex('cache_active_module_permissions');
        return $cache->rememberForever('permissions.all.active', [
            "access_platform_id" => $platform_type_ids
        ], function () use ($platform_type_ids) {
            return $this->newQuery()
                ->where("permissions.type", 'MODULE')
                ->whereIn("permissions.access_platform_id", $platform_type_ids)
                ->get()
                ->toArray();
        });
    }

    /**
     * Obtiene todos los permisos activos sin filtrar por tipo.
     *
     * @return array
     */
    public function getAllActivePermissions(array $platform_type_ids): array
    {

        $cache = new CacheWithIndex('cache_all_active_permissions');

        return $cache->rememberForever('permissions.all.active', [
            "access_platform_id" => $platform_type_ids
        ], function () use ($platform_type_ids) {
            return $this->newQuery()
                ->whereIn("permissions.access_platform_id", $platform_type_ids)
                ->get()
                ->toArray();
        });
    }
    /**
     * Obtiene las claves de permisos activos de una compañía si no se repiten.
     * Si detecta duplicados, retorna un arreglo vacío.
     *
     * @param int $company
     * @return array
     */
    public function getUniqueCompanyPermissionKeys(int $company, array $platform_type_ids): array
    {

        $keys =  $this->newQuery()->join("modules", "permissions.key", "modules.key")
            ->where("modules.company_id", $company)
            ->where("modules.status", 1)
            ->whereIn("permissions.access_platform_id", $platform_type_ids)
            ->get()
            ->pluck('key')
            ->toArray();

        return count($keys) === count(array_unique($keys)) ? $keys : [];
    }


    /**
     * Obtiene las claves de permisos de tipo MODULE activos de una compañía si no se repiten.
     * Si detecta duplicados, retorna un arreglo vacío.
     *
     * @param int $company
     * @return array
     */
    public function getUniqueCompanyModulePermissionKeys(int $company, array $platform_type_ids): array
    {

        $keys =  $this->newQuery()->join("modules", "permissions.key", "modules.key")
            ->where("modules.company_id", $company)
            ->where("permissions.type", 'MODULE')
            ->whereIn("permissions.access_platform_id", $platform_type_ids)
            ->where("modules.status", 1)
            ->get()
            ->pluck('key')
            ->toArray();

        return count($keys) === count(array_unique($keys)) ? $keys : [];
    }
    /**
     * Retorna claves únicas de permisos activos asignados a un rol en una compañía.
     * Si hay duplicados, retorna un arreglo vacío.
     *
     * @param string $role_id
     * @param int $company_id
     * @return array
     */
    public function getUniquePermissionKeysByRoleAndCompany(int $company_id, string $role_id, array $platform_type_ids): array
    {
        $keys = $this->newQuery()->join("role_permissions", "permissions.key", "role_permissions.key")
            ->join("roles", "role_permissions.role_id", "roles.id")
            ->where("role_permissions.role_id", $role_id)
            ->where("roles.company_id", $company_id)
            ->where("role_permissions.status", 1)
            ->whereIn("permissions.access_platform_id", $platform_type_ids)
            ->get()
            ->pluck('key')
            ->toArray();

        return count($keys) === count(array_unique($keys)) ? $keys : [];
    }

    /**
     * Obtiene todas las plataformas de acceso disponibles.
     *
     * @return array
     */
    public function getAccessPlatforms(?int $platform_type = null): array
    {

        $cache = new CacheWithIndex('cache_access_platforms');

        return $cache->rememberForever('access_platforms.list', [
            'platform_type' => $platform_type,
        ], function () use ($platform_type) {
            return AccessPlatform::select(
                "access_platforms.id",
                "access_platforms.name_es",
                "access_platforms.name_en"
            )->when(
                $platform_type !== null,
                fn($q) => $q->where('access_platforms.id', $platform_type)
            )->get()->toArray();
        });
    }
}
