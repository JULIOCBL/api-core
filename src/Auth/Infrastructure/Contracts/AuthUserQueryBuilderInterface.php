<?php

namespace Src\Auth\Infrastructure\Contracts;

use Src\Shared\Infrastructure\Persistence\Eloquent\Models\User;

/**
 * Contrato para consultas de usuario autenticado en infraestructura.
 */
interface AuthUserQueryBuilderInterface
{
    /**
     * @param string $user_id
     * @return User|null
     */
    public function findById(string $user_id): ?User;
}
