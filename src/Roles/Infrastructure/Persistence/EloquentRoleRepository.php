<?php

namespace Src\Roles\Infrastructure\Persistence;

use Illuminate\Support\Facades\DB;
use Src\Roles\Domain\Contracts\RoleRepositoryInterface;
use Src\Roles\Domain\Entities\Role;
use Src\Roles\Domain\ValueObjects\RoleDraft;
use Src\Shared\Infrastructure\Persistence\Eloquent\Models\Role as RoleModel;

/**
 * Adaptador de infraestructura para roles usando Eloquent.
 */
class EloquentRoleRepository implements RoleRepositoryInterface
{
    /**
     * @param RoleDraft $role_draft
     * @return Role
     */
    public function create(RoleDraft $role_draft): Role
    {
        return DB::transaction(function () use ($role_draft): Role {
            $role_model = RoleModel::query()->create([
                'company_id' => $role_draft->getCompanyId(),
                'user_type_id' => $role_draft->getUserTypeId(),
                'name' => $role_draft->getName(),
                'required_mail' => $role_draft->getRequiredMail(),
                'status' => $role_draft->getStatus(),
            ]);

            return new Role(
                id: (string) $role_model->id,
                company_id: (int) $role_model->company_id,
                user_type_id: (int) $role_model->user_type_id,
                name: (string) $role_model->name,
                required_mail: (bool) $role_model->required_mail,
                status: (bool) $role_model->status
            );
        });
    }
}
