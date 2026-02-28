<?php

namespace Src\Roles\Infrastructure\Http\Presenters;

use Src\Roles\Domain\Entities\Role;

/**
 * Presenter para convertir entidad Role a respuesta HTTP.
 */
class RoleResponsePresenter
{
    /**
     * @param Role $role
     * @return array<string, mixed>
     */
    public function present(Role $role): array
    {
        return [
            'id' => $role->getId(),
            'company_id' => $role->getCompanyId(),
            'user_type_id' => $role->getUserTypeId(),
            'name' => $role->getName(),
            'required_mail' => $role->getRequiredMail(),
            'status' => $role->getStatus(),
        ];
    }
}
