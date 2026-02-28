<?php

namespace Src\UserTypes\Domain\Entities;

/**
 * Entidad de dominio para tipo de asistente.
 */
class UserType
{
    /**
     * @param int $id
     * @param string $name
     * @param string $constant
     * @param bool $required_mail
     */
    public function __construct(
        private int $id,
        private string $name,
        private string $constant,
        private bool $required_mail
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getConstant(): string
    {
        return $this->constant;
    }

    public function getRequiredMail(): bool
    {
        return $this->required_mail;
    }
}
