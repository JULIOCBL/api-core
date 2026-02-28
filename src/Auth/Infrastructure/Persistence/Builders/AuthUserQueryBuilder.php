<?php

namespace Src\Auth\Infrastructure\Persistence\Builders;

use Src\Auth\Infrastructure\Contracts\AuthUserQueryBuilderInterface;
use Src\Shared\Infrastructure\Persistence\Eloquent\Models\User;

/**
 * Builder para consultas de usuario autenticado con joins requeridos.
 */
class AuthUserQueryBuilder implements AuthUserQueryBuilderInterface
{
    /**
     * @param string $user_id
     * @return User|null
     */
    public function findById(string $user_id): ?User
    {
        return User::query()
            ->select([
                'users.id',
                'users.name',
                'users.last_name',
                'users.phone',
                'users.email',
                'users.username',
                'users.user_status_id',
                'users.change_password',
                'roles.id as role_id',
                'roles.name as role_name',
                'roles.deleted_at as role_deleted_at',
                'roles.company_id as role_company_id',
                'user_types.id as user_type_id',
                'user_types.name as user_type_name',
            ])
            ->join('roles', 'users.role_id', 'roles.id')
            ->join('user_types', 'roles.user_type_id', 'user_types.id')
            ->where('users.id', $user_id)
            ->whereNull('users.deleted_at')
            ->first();
    }
}
