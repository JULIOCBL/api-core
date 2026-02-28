<?php

namespace Src\Roles\Domain\ValueObjects;

/**
 * Value object con datos para crear un rol.
 */
class RoleDraft
{
    /**
     * @param int $company_id
     * @param int $user_type_id
     * @param string $name
     * @param bool $required_mail
     * @param bool $status
     */
    public function __construct(
        private int $company_id,
        private int $user_type_id,
        private string $name,
        private bool $required_mail,
        private bool $status
    ) {
    }

    public function getCompanyId(): int
    {
        return $this->company_id;
    }

    public function getUserTypeId(): int
    {
        return $this->user_type_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRequiredMail(): bool
    {
        return $this->required_mail;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }
}
