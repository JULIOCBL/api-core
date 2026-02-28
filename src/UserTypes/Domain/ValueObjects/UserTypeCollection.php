<?php

namespace Src\UserTypes\Domain\ValueObjects;

use Src\UserTypes\Domain\Entities\UserType;

/**
 * Colección de tipos de asistente.
 */
class UserTypeCollection
{
    /**
     * @param array<int, UserType> $items
     */
    public function __construct(private array $items)
    {
    }

    /**
     * @return array<int, UserType>
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
