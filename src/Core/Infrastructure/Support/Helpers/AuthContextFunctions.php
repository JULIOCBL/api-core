<?php

use Src\Core\Infrastructure\Support\Helpers\AuthUserHelper;
use Src\Core\Infrastructure\Support\Helpers\PlatformTypeHelper;
use Src\Core\Infrastructure\Support\Builders\CompanyContextBuilder;

if (!function_exists('authUserTypeId')) {
    /**
     * Obtiene el user_type_id del usuario autenticado.
     *
     * @return int
     */
    function authUserTypeId(): int
    {
        return AuthUserHelper::getUserTypeId();
    }
}

if (!function_exists('isRoot')) {
    /**
     * @return bool
     */
    function isRoot(): bool
    {
        return AuthUserHelper::isRoot();
    }
}

if (!function_exists('isSuperAdmin')) {
    /**
     * @return bool
     */
    function isSuperAdmin(): bool
    {
        return AuthUserHelper::isSuperAdmin();
    }
}

if (!function_exists('isAdmin')) {
    /**
     * @return bool
     */
    function isAdmin(): bool
    {
        return AuthUserHelper::isAdmin();
    }
}

if (!function_exists('isUser')) {
    /**
     * @return bool
     */
    function isUser(): bool
    {
        return AuthUserHelper::isUser();
    }
}

if (!function_exists('platformType')) {
    /**
     * Obtiene el platform_type actual del request.
     *
     * @return int|null
     */
    function platformType(): ?int
    {
        return PlatformTypeHelper::getCurrent();
    }
}

if (!function_exists('platformTypeName')) {
    /**
     * Obtiene el nombre del platform_type actual o del enviado.
     *
     * @param int|null $platform_type
     * @return string
     */
    function platformTypeName(?int $platform_type = null): string
    {
        $resolved_platform_type = $platform_type ?? PlatformTypeHelper::getCurrent();
        if ($resolved_platform_type === null) {
            return 'unknown';
        }

        return PlatformTypeHelper::toName($resolved_platform_type);
    }
}

if (!function_exists('isWeb')) {
    /**
     * @return bool
     */
    function isWeb(): bool
    {
        return PlatformTypeHelper::isWeb();
    }
}

if (!function_exists('isMobile')) {
    /**
     * @return bool
     */
    function isMobile(): bool
    {
        return PlatformTypeHelper::isMobile();
    }
}

if (!function_exists('isDesktop')) {
    /**
     * @return bool
     */
    function isDesktop(): bool
    {
        return PlatformTypeHelper::isDesktop();
    }
}

if (!function_exists('isIntegration')) {
    /**
     * @return bool
     */
    function isIntegration(): bool
    {
        return PlatformTypeHelper::isIntegration();
    }
}

if (!function_exists('company')) {
    /**
     * Resuelve el company_id activo en el contexto autenticado.
     *
     * @return int
     */
    function company(): int
    {
        $company_context_builder = app(CompanyContextBuilder::class);

        return $company_context_builder->build();
    }
}

if (!function_exists('permissions')) {
    /**
     * Obtiene los permisos del contexto autenticado actual.
     *
     * @return array<int, string>
     */
    function permissions(): array
    {
        $permissions = request()->attributes->get('auth_permissions');

        if (!is_array($permissions)) {
            return [];
        }

        $normalized_permissions = [];
        foreach ($permissions as $permission_key) {
            if (is_string($permission_key) && $permission_key !== '') {
                $normalized_permissions[] = $permission_key;
            }
        }

        return $normalized_permissions;
    }
}

if (!function_exists('hasPermission')) {
    /**
     * Verifica si una key/constante existe en los permisos del usuario autenticado.
     *
     * @param string $permission_key
     * @return bool
     */
    function hasPermission(string $permission_key): bool
    {
        if ($permission_key === '') {
            return false;
        }

        return in_array($permission_key, permissions(), true);
    }
}
