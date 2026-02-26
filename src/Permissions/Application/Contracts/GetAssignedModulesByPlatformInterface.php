<?php

namespace Src\Permissions\Application\Contracts;

/**
 * Caso de uso para construir árboles de módulos asignados por plataforma.
 */
interface GetAssignedModulesByPlatformInterface
{
    /**
     * Construye el árbol de módulos asignados a la compañía por plataforma.
     *
     * @param int $company_id
     * @return array<int, array<string, mixed>>
     */
    public function buildModuleTreeByPlatform(int $company_id): array;

    /**
     * Construye el árbol filtrado por compañía, rol y plataforma opcional.
     *
     * @param int $company_id
     * @param string $role_id
     * @param int|null $platform_type
     * @return array<int, array<string, mixed>>
     */
    public function buildFilteredModuleTreeByCompanyAndRole(int $company_id, string $role_id, ?int $platform_type = null): array;
}
