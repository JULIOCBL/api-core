<?php

namespace Src\Companies\Application\UseCases;

use Src\Companies\Application\DataTransferObjects\UpdateCompanyInput;
use Src\Companies\Domain\Contracts\CompanyRepositoryInterface;
use Src\Companies\Domain\Entities\Company;
use Src\Companies\Domain\ValueObjects\CompanyUpdate;

/**
 * Caso de uso para actualización de compañías.
 */
class UpdateCompanyUseCase
{
    /**
     * @param CompanyRepositoryInterface $company_repository
     */
    public function __construct(private CompanyRepositoryInterface $company_repository)
    {
    }

    /**
     * Ejecuta el flujo de actualización parcial.
     *
     * @param UpdateCompanyInput $update_company_input
     * @return Company
     */
    public function execute(UpdateCompanyInput $update_company_input): Company
    {
        $company_update = new CompanyUpdate(
            name: $update_company_input->getName(),
            has_name: $update_company_input->hasName(),
            commercial_name: $update_company_input->getCommercialName(),
            has_commercial_name: $update_company_input->hasCommercialName(),
            bussiness_name: $update_company_input->getBussinessName(),
            has_bussiness_name: $update_company_input->hasBussinessName(),
            rfc: $update_company_input->getRfc(),
            has_rfc: $update_company_input->hasRfc(),
            contact_phone: $update_company_input->getContactPhone(),
            has_contact_phone: $update_company_input->hasContactPhone(),
            email: $update_company_input->getEmail(),
            has_email: $update_company_input->hasEmail(),
            primary_color: $update_company_input->getPrimaryColor(),
            has_primary_color: $update_company_input->hasPrimaryColor(),
            secondary_color: $update_company_input->getSecondaryColor(),
            has_secondary_color: $update_company_input->hasSecondaryColor(),
            tertiary_color: $update_company_input->getTertiaryColor(),
            has_tertiary_color: $update_company_input->hasTertiaryColor(),
            image_logo: $update_company_input->getImageLogo(),
            has_image_logo: $update_company_input->hasImageLogo(),
            status: $update_company_input->getStatus(),
            has_status: $update_company_input->hasStatus()
        );

        return $this->company_repository->update($update_company_input->getCompanyId(), $company_update);
    }
}
