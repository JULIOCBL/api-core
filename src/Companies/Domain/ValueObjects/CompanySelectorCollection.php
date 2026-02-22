<?php

namespace Src\Companies\Domain\ValueObjects;

use Src\Companies\Domain\Entities\CompanySelectorItem;

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
