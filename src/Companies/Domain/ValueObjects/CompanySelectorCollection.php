<?php

namespace Src\Companies\Domain\ValueObjects;

use Src\Companies\Domain\Entities\CompanySelectorItem;

/**
 * Colección de items para selector de compañías.
 */
class CompanySelectorCollection
{
    /**
     * @param CompanySelectorItem[] $items
     */
    public function __construct(private array $items)
    {
    }

    /**
     * @return CompanySelectorItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
