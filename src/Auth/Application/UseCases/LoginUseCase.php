<?php

namespace Src\Auth\Application\UseCases;

use Src\Auth\Application\DataTransferObjects\LoginInput;
use Src\Auth\Domain\Contracts\AuthGatewayInterface;
use Src\Auth\Domain\ValueObjects\AuthSession;
use Src\Permissions\Application\Contracts\GetAssignedModulesByPlatformInterface;
use Src\Permissions\Domain\Services\PermissionsTreeService;

/**
 * Caso de uso para autenticación de usuario.
 */
class LoginUseCase
{
    /**
     * @param AuthGatewayInterface $auth_gateway
     * @param GetAssignedModulesByPlatformInterface $assigned_modules_by_platform
     */
    public function __construct(
        private AuthGatewayInterface $auth_gateway,
        private GetAssignedModulesByPlatformInterface $assigned_modules_by_platform
    ) {
    }

    /**
     * @param LoginInput $login_input
     * @return AuthSession
     */
    public function execute(LoginInput $login_input): AuthSession
    {
        $auth_session = $this->auth_gateway->login(
            $login_input->getIdentifier(),
            $login_input->getPassword(),
            $login_input->getClientContext()
        );

        $user = $auth_session->getUser();
        $permissions = [];

        if ($user->getUserTypeId() !== 1 && $user->getCompanyId() !== null) {
            $tree_by_platform = $this->assigned_modules_by_platform->buildFilteredModuleTreeByCompanyAndRole(
                $user->getCompanyId(),
                $user->getRoleId(),
                $login_input->getClientContext()->getPlatformType()
            );

            $assigned_modules = isset($tree_by_platform[0]['assigned_modules']) && is_array($tree_by_platform[0]['assigned_modules'])
                ? $tree_by_platform[0]['assigned_modules']
                : [];

            $permissions = PermissionsTreeService::getEnabledPermissionKeys($assigned_modules);
        }

        return $auth_session->withPermissions($permissions);
    }
}
