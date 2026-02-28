<?php

namespace Src\UserTypes\Infrastructure\Http\Presenters;

use Src\UserTypes\Domain\Entities\UserType;
use Src\UserTypes\Domain\ValueObjects\UserTypeCollection;

/**
 * Presenter para tipos de asistente.
 */
class UserTypeResponsePresenter
{
    /**
     * @param UserTypeCollection $user_type_collection
     * @return array<int, array<string, mixed>>
     */
    public function presentCollection(UserTypeCollection $user_type_collection): array
    {
        $items = [];

        foreach ($user_type_collection->getItems() as $user_type) {
            $items[] = $this->present($user_type);
        }

        return $items;
    }

    /**
     * @param UserType $user_type
     * @return array<string, mixed>
     */
    private function present(UserType $user_type): array
    {
        return [
            'id' => $user_type->getId(),
            'name' => $user_type->getName(),
            'constant' => $user_type->getConstant(),
            'required_mail' => $user_type->getRequiredMail(),
        ];
    }
}
