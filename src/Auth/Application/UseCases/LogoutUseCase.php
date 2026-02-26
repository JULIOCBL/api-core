<?php

namespace Src\Auth\Application\UseCases;

use Src\Auth\Application\DataTransferObjects\LogoutInput;
use Src\Auth\Domain\Contracts\AuthGatewayInterface;

/**
 * Caso de uso para cierre de sesión.
 */
class LogoutUseCase
{
    /**
     * @param AuthGatewayInterface $auth_gateway
     */
    public function __construct(private AuthGatewayInterface $auth_gateway)
    {
    }

    /**
     * @param LogoutInput $logout_input
     * @return void
     */
    public function execute(LogoutInput $logout_input): void
    {
        $this->auth_gateway->logout($logout_input->getAccessToken());
    }
}
