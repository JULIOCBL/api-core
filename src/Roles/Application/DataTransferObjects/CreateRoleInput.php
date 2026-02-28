<?php

namespace Src\Roles\Application\DataTransferObjects;

/**
 * DTO de entrada para crear un rol.
 */
class CreateRoleInput
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

    /**
     * @param array<string, mixed> $role_data
     * @param int $company_id
     * @return self
     */
    public static function fromArray(array $role_data, int $company_id): self
    {
        return new self(
            company_id: $company_id,
            user_type_id: (int) $role_data['user_type_id'],
            name: (string) $role_data['name'],
            required_mail: isset($role_data['required_mail']) ? (bool) $role_data['required_mail'] : false,
            status: isset($role_data['status']) ? (bool) $role_data['status'] : true
        );
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
