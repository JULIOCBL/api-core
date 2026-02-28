<?php

namespace Src\UserTypes\Infrastructure\Persistence;

use Src\Shared\Infrastructure\Persistence\Eloquent\Models\UserType as UserTypeModel;
use Src\UserTypes\Domain\Contracts\UserTypeRepositoryInterface;
use Src\UserTypes\Domain\Entities\UserType;
use Src\UserTypes\Domain\ValueObjects\UserTypeCollection;

/**
 * Adaptador de infraestructura para tipos de asistente usando Eloquent.
 */
class EloquentUserTypeRepository implements UserTypeRepositoryInterface
{
    /**
     * @param int $auth_user_type_id
     * @return UserTypeCollection
     */
    public function getAssignableByAuthUserType(int $auth_user_type_id): UserTypeCollection
    {
        $user_type_models = UserTypeModel::query()
            ->select(['id', 'name', 'constant', 'required_mail'])
            ->where('id', '>', $auth_user_type_id)
            ->orderBy('id')
            ->get();

        $items = [];
        foreach ($user_type_models as $user_type_model) {
            $items[] = new UserType(
                id: (int) $user_type_model->id,
                name: (string) $user_type_model->name,
                constant: (string) $user_type_model->constant,
                required_mail: (bool) $user_type_model->required_mail
            );
        }

        return new UserTypeCollection($items);
    }
}
