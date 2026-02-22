<?php

namespace Src\Companies\Domain\Entities;

class Company
{
    public function __construct(
        private int $id,
        private string $name,
        private string $commercial_name,
        private ?string $bussiness_name,
        private ?string $rfc,
        private ?string $contact_phone,
        private ?string $email,
        private ?string $primary_color,
        private ?string $secondary_color,
        private ?string $tertiary_color,
        private ?string $image_logo,
        private bool $status
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

    public function getCommercialName(): string
    {
        return $this->commercial_name;
    }

    public function getBussinessName(): ?string
    {
        return $this->bussiness_name;
    }

    public function getRfc(): ?string
    {
        return $this->rfc;
    }

    public function getContactPhone(): ?string
    {
        return $this->contact_phone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPrimaryColor(): ?string
    {
        return $this->primary_color;
    }

    public function getSecondaryColor(): ?string
    {
        return $this->secondary_color;
    }

    public function getTertiaryColor(): ?string
    {
        return $this->tertiary_color;
    }

    public function getImageLogo(): ?string
    {
        return $this->image_logo;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }
}
