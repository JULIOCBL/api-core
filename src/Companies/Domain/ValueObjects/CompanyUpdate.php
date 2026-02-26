<?php

namespace Src\Companies\Domain\ValueObjects;

/**
 * Value object para actualización parcial de compañía.
 * Incluye banderas `has_*` para distinguir ausencia de campo y `null`.
 */
class CompanyUpdate
{
    public function __construct(
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
