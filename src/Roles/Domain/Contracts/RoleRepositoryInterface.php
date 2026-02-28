<?php

namespace Src\Roles\Domain\Contracts;

use Src\Roles\Domain\Entities\Role;
use Src\Roles\Domain\ValueObjects\RoleDraft;

/**
 * Puerto de salida para persistencia de roles.
 */
interface RoleRepositoryInterface
{
    /**
     * @param RoleDraft $role_draft
     * @return Role
     */
    public function create(RoleDraft $role_draft): Role;
}
