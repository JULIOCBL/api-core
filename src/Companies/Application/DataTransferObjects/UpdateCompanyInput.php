<?php

namespace Src\Companies\Application\DataTransferObjects;

/**
 * DTO de entrada para actualización parcial de compañía.
 */
class UpdateCompanyInput
{
    /**
     * @param int $company_id
     * @param string|null $name
     * @param bool $has_name
     * @param string|null $commercial_name
     * @param bool $has_commercial_name
     * @param string|null $bussiness_name
     * @param bool $has_bussiness_name
     * @param string|null $rfc
     * @param bool $has_rfc
     * @param string|null $contact_phone
     * @param bool $has_contact_phone
     * @param string|null $email
     * @param bool $has_email
     * @param string|null $primary_color
     * @param bool $has_primary_color
     * @param string|null $secondary_color
     * @param bool $has_secondary_color
     * @param string|null $tertiary_color
     * @param bool $has_tertiary_color
     * @param string|null $image_logo
     * @param bool $has_image_logo
     * @param bool|null $status
     * @param bool $has_status
     */
    public function __construct(
        private int $company_id,
        private ?string $name,
        private bool $has_name,
        private ?string $commercial_name,
        private bool $has_commercial_name,
        private ?string $bussiness_name,
        private bool $has_bussiness_name,
        private ?string $rfc,
        private bool $has_rfc,
        private ?string $contact_phone,
        private bool $has_contact_phone,
        private ?string $email,
        private bool $has_email,
        private ?string $primary_color,
        private bool $has_primary_color,
        private ?string $secondary_color,
        private bool $has_secondary_color,
        private ?string $tertiary_color,
        private bool $has_tertiary_color,
        private ?string $image_logo,
        private bool $has_image_logo,
        private ?bool $status,
        private bool $has_status
    ) {
    }

    /**
     * Crea el DTO desde el arreglo validado del request.
     *
     * @param int $company_id
     * @param array<string, mixed> $company_data
     * @return self
     */
    public static function fromArray(int $company_id, array $company_data): self
    {
        return new self(
            company_id: $company_id,
            name: $company_data['name'] ?? null,
            has_name: array_key_exists('name', $company_data),
            commercial_name: $company_data['commercial_name'] ?? null,
            has_commercial_name: array_key_exists('commercial_name', $company_data),
            bussiness_name: $company_data['bussiness_name'] ?? null,
            has_bussiness_name: array_key_exists('bussiness_name', $company_data),
            rfc: $company_data['rfc'] ?? null,
            has_rfc: array_key_exists('rfc', $company_data),
            contact_phone: $company_data['contact_phone'] ?? null,
            has_contact_phone: array_key_exists('contact_phone', $company_data),
            email: $company_data['email'] ?? null,
            has_email: array_key_exists('email', $company_data),
            primary_color: $company_data['primary_color'] ?? null,
            has_primary_color: array_key_exists('primary_color', $company_data),
            secondary_color: $company_data['secondary_color'] ?? null,
            has_secondary_color: array_key_exists('secondary_color', $company_data),
            tertiary_color: $company_data['tertiary_color'] ?? null,
            has_tertiary_color: array_key_exists('tertiary_color', $company_data),
            image_logo: $company_data['image_logo'] ?? null,
            has_image_logo: array_key_exists('image_logo', $company_data),
            status: $company_data['status'] ?? null,
            has_status: array_key_exists('status', $company_data)
        );
    }

    public function getCompanyId(): int
    {
        return $this->company_id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function hasName(): bool
    {
        return $this->has_name;
    }

    public function getCommercialName(): ?string
    {
        return $this->commercial_name;
    }

    public function hasCommercialName(): bool
    {
        return $this->has_commercial_name;
    }

    public function getBussinessName(): ?string
    {
        return $this->bussiness_name;
    }

    public function hasBussinessName(): bool
    {
        return $this->has_bussiness_name;
    }

    public function getRfc(): ?string
    {
        return $this->rfc;
    }

    public function hasRfc(): bool
    {
        return $this->has_rfc;
    }

    public function getContactPhone(): ?string
    {
        return $this->contact_phone;
    }

    public function hasContactPhone(): bool
    {
        return $this->has_contact_phone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function hasEmail(): bool
    {
        return $this->has_email;
    }

    public function getPrimaryColor(): ?string
    {
        return $this->primary_color;
    }

    public function hasPrimaryColor(): bool
    {
        return $this->has_primary_color;
    }

    public function getSecondaryColor(): ?string
    {
        return $this->secondary_color;
    }

    public function hasSecondaryColor(): bool
    {
        return $this->has_secondary_color;
    }

    public function getTertiaryColor(): ?string
    {
        return $this->tertiary_color;
    }

    public function hasTertiaryColor(): bool
    {
        return $this->has_tertiary_color;
    }

    public function getImageLogo(): ?string
    {
        return $this->image_logo;
    }

    public function hasImageLogo(): bool
    {
        return $this->has_image_logo;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function hasStatus(): bool
    {
        return $this->has_status;
    }
}
