<?php

namespace Src\UserTypes\Application\UseCases;

use Src\UserTypes\Domain\Contracts\UserTypeRepositoryInterface;
use Src\UserTypes\Domain\ValueObjects\UserTypeCollection;

/**
 * Caso de uso para obtener tipos de asistente asignables por jerarquía.
 */
class GetAssignableUserTypesUseCase
{
    /**
     * @param UserTypeRepositoryInterface $user_type_repository
     */
    public function __construct(private UserTypeRepositoryInterface $user_type_repository)
    {
    }

    /**
     * @param int $auth_user_type_id
     * @return UserTypeCollection
     */
    public function execute(int $auth_user_type_id): UserTypeCollection
    {
        return $this->user_type_repository->getAssignableByAuthUserType($auth_user_type_id);
    }
}
