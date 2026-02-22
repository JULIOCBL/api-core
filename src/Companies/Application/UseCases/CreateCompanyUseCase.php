<?php

namespace Src\Companies\Application\UseCases;

use Src\Companies\Application\DataTransferObjects\CreateCompanyInput;
use Src\Companies\Domain\Contracts\CompanyRepositoryInterface;
use Src\Companies\Domain\Entities\Company;
use Src\Companies\Domain\ValueObjects\CompanyDraft;

class CreateCompanyUseCase
{
    public function __construct(private CompanyRepositoryInterface $company_repository)
    {
    }

    public function execute(CreateCompanyInput $create_company_input): Company
    {
        $company_draft = new CompanyDraft(
            name: $create_company_input->getName(),
            commercial_name: $create_company_input->getCommercialName(),
            bussiness_name: $create_company_input->getBussinessName(),
            rfc: $create_company_input->getRfc(),
            contact_phone: $create_company_input->getContactPhone(),
            email: $create_company_input->getEmail(),
            primary_color: $create_company_input->getPrimaryColor(),
            secondary_color: $create_company_input->getSecondaryColor(),
            tertiary_color: $create_company_input->getTertiaryColor(),
            image_logo: $create_company_input->getImageLogo(),
            status: $create_company_input->getStatus()
        );

        return $this->company_repository->create($company_draft);
    }
}
