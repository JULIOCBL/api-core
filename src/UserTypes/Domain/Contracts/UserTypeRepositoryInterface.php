<?php

namespace Src\UserTypes\Domain\Contracts;

use Src\UserTypes\Domain\ValueObjects\UserTypeCollection;

/**
 * Puerto de salida para consulta de tipos de asistente.
 */
interface UserTypeRepositoryInterface
{
    /**
     * Obtiene tipos disponibles desde el nivel del usuario autenticado hacia abajo.
     *
     * @param int $auth_user_type_id
     * @return UserTypeCollection
     */
    public function getAssignableByAuthUserType(int $auth_user_type_id): UserTypeCollection;
}
