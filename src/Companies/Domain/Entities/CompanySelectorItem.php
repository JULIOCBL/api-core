<?php

namespace Src\Companies\Domain\Entities;

/**
 * Entidad liviana para opciones de selector de compañías.
 */
class CompanySelectorItem
{
    public function __construct(
        private int $id,
        private string $commercial_name
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCommercialName(): string
    {
        return $this->commercial_name;
    }
}
