<?php

namespace Src\Auth\Domain\Contracts;

use Src\Auth\Domain\ValueObjects\AuthSession;
use Src\Auth\Domain\ValueObjects\ClientContext;

/**
 * Puerto de salida para autenticación y gestión de sesión.
 */
interface AuthGatewayInterface
{
    /**
     * @param string $identifier
     * @param string $password
     * @param ClientContext $client_context
     * @return AuthSession
     */
    public function login(string $identifier, string $password, ClientContext $client_context): AuthSession;

    /**
     * @param string $refresh_token
     * @return AuthSession
     */
    public function refresh(string $refresh_token): AuthSession;

    /**
     * @param string $access_token
     * @return void
     */
    public function logout(string $access_token): void;
}
