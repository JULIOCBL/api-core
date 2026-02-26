<?php

namespace Src\Permissions\Domain\Contracts;

/**
 * Contrato del repositorio de permisos para consultas de acceso por plataforma,
 * compañía y rol.
 */
interface PermissionInterface
{
    /**
     * Obtiene plataformas de acceso disponibles.
     *
     * @param int|null $platform_type Filtra por plataforma específica cuando aplica.
     * @return array<int, array<string, mixed>>
     */
    public function getAccessPlatforms(?int $platform_type = null): array;

    /**
     * Obtiene permisos activos por plataforma.
     *
     * @param array<int, int> $platform_type_ids
     * @return array<int, array<string, mixed>>
     */
    public function getActiveModulePermissions(array $platform_type_ids): array;

    /**
     * Obtiene llaves únicas de módulos asignados a una compañía.
     *
     * @param int $company_id
     * @param array<int, int> $platform_type_ids
     * @return array<int, string>
     */
    public function getUniqueCompanyModulePermissionKeys(int $company_id, array $platform_type_ids): array;

    /**
     * Obtiene todos los permisos activos por plataforma.
     *
     * @param array<int, int> $platform_type_ids
     * @return array<int, array<string, mixed>>
     */
    public function getAllActivePermissions(array $platform_type_ids): array;

    /**
     * Obtiene llaves únicas de permisos activos por compañía.
     *
     * @param int $company_id
     * @param array<int, int> $platform_type_ids
     * @return array<int, string>
     */
    public function getUniqueCompanyPermissionKeys(int $company_id, array $platform_type_ids): array;

    /**
     * Obtiene llaves de permisos por rol y compañía.
     *
     * @param int $company_id
     * @param string $role_id
     * @param array<int, int> $platform_type_ids
     * @return array<int, string>
     */
    public function getUniquePermissionKeysByRoleAndCompany(int $company_id, string $role_id, array $platform_type_ids): array;
}
