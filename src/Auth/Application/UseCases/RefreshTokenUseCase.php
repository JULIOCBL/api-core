<?php

namespace Src\Auth\Application\UseCases;

use Src\Auth\Application\DataTransferObjects\RefreshTokenInput;
use Src\Auth\Domain\Contracts\AuthGatewayInterface;
use Src\Auth\Domain\ValueObjects\AuthSession;

/**
 * Caso de uso para refrescar sesión.
 */
class RefreshTokenUseCase
{
    /**
     * @param AuthGatewayInterface $auth_gateway
     */
    public function __construct(private AuthGatewayInterface $auth_gateway)
    {
    }

    /**
     * @param RefreshTokenInput $refresh_token_input
     * @return AuthSession
     */
    public function execute(RefreshTokenInput $refresh_token_input): AuthSession
    {
        return $this->auth_gateway->refresh($refresh_token_input->getRefreshToken());
    }
}
