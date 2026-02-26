<?php

namespace Src\Companies\Application\DataTransferObjects;

/**
 * DTO de entrada para crear una compañía.
 */
class CreateCompanyInput
{
    /**
     * @param string $name
     * @param string $commercial_name
     * @param string|null $bussiness_name
     * @param string|null $rfc
     * @param string|null $contact_phone
     * @param string|null $email
     * @param string|null $primary_color
     * @param string|null $secondary_color
     * @param string|null $tertiary_color
     * @param string|null $image_logo
     * @param bool $status
     */
    public function __construct(
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

    /**
     * Crea el DTO desde el arreglo validado del request.
     *
     * @param array<string, mixed> $company_data
     * @return self
     */
    public static function fromArray(array $company_data): self
    {
        return new self(
            name: (string) $company_data['name'],
            commercial_name: (string) $company_data['commercial_name'],
            bussiness_name: isset($company_data['bussiness_name']) ? (string) $company_data['bussiness_name'] : null,
            rfc: isset($company_data['rfc']) ? (string) $company_data['rfc'] : null,
            contact_phone: isset($company_data['contact_phone']) ? (string) $company_data['contact_phone'] : null,
            email: isset($company_data['email']) ? (string) $company_data['email'] : null,
            primary_color: isset($company_data['primary_color']) ? (string) $company_data['primary_color'] : null,
            secondary_color: isset($company_data['secondary_color']) ? (string) $company_data['secondary_color'] : null,
            tertiary_color: isset($company_data['tertiary_color']) ? (string) $company_data['tertiary_color'] : null,
            image_logo: isset($company_data['image_logo']) ? (string) $company_data['image_logo'] : null,
            status: isset($company_data['status']) ? (bool) $company_data['status'] : true
        );
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
