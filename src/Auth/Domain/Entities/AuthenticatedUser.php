<?php

namespace Src\Auth\Domain\Entities;

/**
 * Entidad de dominio para usuario autenticado.
 */
class AuthenticatedUser
{
    /**
     * @param string $id
     * @param string|null $name
     * @param string|null $last_name
     * @param string|null $email
     * @param string|null $username
     * @param string|null $phone
     * @param bool $change_password
     * @param string $role_id
     * @param string $role_name
     * @param int $user_status_id
     * @param int $user_type_id
     * @param string|null $user_type_name
     * @param int|null $company_id
     * @param string|null $company_name
     * @param string|null $company_commercial_name
     * @param string|null $company_bussiness_name
     * @param string|null $company_email
     * @param string|null $company_primary_color
     * @param string|null $company_secondary_color
     * @param string|null $company_tertiary_color
     */
    public function __construct(
        private string $id,
        private ?string $name,
        private ?string $last_name,
        private ?string $email,
        private ?string $username,
        private ?string $phone,
        private bool $change_password,
        private string $role_id,
        private string $role_name,
        private int $user_status_id
        ,
        private int $user_type_id,
        private ?string $user_type_name,
        private ?int $company_id,
        private ?string $company_name,
        private ?string $company_commercial_name,
        private ?string $company_bussiness_name,
        private ?string $company_email,
        private ?string $company_primary_color,
        private ?string $company_secondary_color,
        private ?string $company_tertiary_color
    ) {
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @return bool
     */
    public function getChangePassword(): bool
    {
        return $this->change_password;
    }

    /**
     * @return string
     */
    public function getRoleId(): string
    {
        return $this->role_id;
    }

    /**
     * @return string
     */
    public function getRoleName(): string
    {
        return $this->role_name;
    }

    /**
     * @return int
     */
    public function getUserStatusId(): int
    {
        return $this->user_status_id;
    }

    /**
     * @return int
     */
    public function getUserTypeId(): int
    {
        return $this->user_type_id;
    }

    /**
     * @return string|null
     */
    public function getUserTypeName(): ?string
    {
        return $this->user_type_name;
    }

    /**
     * @return int|null
     */
    public function getCompanyId(): ?int
    {
        return $this->company_id;
    }

    /**
     * @return string|null
     */
    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    /**
     * @return string|null
     */
    public function getCompanyCommercialName(): ?string
    {
        return $this->company_commercial_name;
    }

    /**
     * @return string|null
     */
    public function getCompanyBussinessName(): ?string
    {
        return $this->company_bussiness_name;
    }

    /**
     * @return string|null
     */
    public function getCompanyEmail(): ?string
    {
        return $this->company_email;
    }

    /**
     * @return string|null
     */
    public function getCompanyPrimaryColor(): ?string
    {
        return $this->company_primary_color;
    }

    /**
     * @return string|null
     */
    public function getCompanySecondaryColor(): ?string
    {
        return $this->company_secondary_color;
    }

    /**
     * @return string|null
     */
    public function getCompanyTertiaryColor(): ?string
    {
        return $this->company_tertiary_color;
    }
}
