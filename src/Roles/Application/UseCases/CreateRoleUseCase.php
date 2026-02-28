<?php

namespace Src\Roles\Application\UseCases;

use Src\Roles\Application\DataTransferObjects\CreateRoleInput;
use Src\Roles\Domain\Contracts\RoleRepositoryInterface;
use Src\Roles\Domain\Entities\Role;
use Src\Roles\Domain\ValueObjects\RoleDraft;

/**
 * Caso de uso para crear un rol.
 */
class CreateRoleUseCase
{
    /**
     * @param RoleRepositoryInterface $role_repository
     */
    public function __construct(private RoleRepositoryInterface $role_repository)
    {
    }

    /**
     * @param CreateRoleInput $create_role_input
     * @return Role
     */
    public function execute(CreateRoleInput $create_role_input): Role
    {
        $role_draft = new RoleDraft(
            company_id: $create_role_input->getCompanyId(),
            user_type_id: $create_role_input->getUserTypeId(),
            name: $create_role_input->getName(),
            required_mail: $create_role_input->getRequiredMail(),
            status: $create_role_input->getStatus()
        );

        return $this->role_repository->create($role_draft);
    }
}
