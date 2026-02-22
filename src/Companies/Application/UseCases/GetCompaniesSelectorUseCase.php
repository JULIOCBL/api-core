<?php

namespace Src\Companies\Application\UseCases;

use Src\Companies\Domain\Contracts\CompanyRepositoryInterface;
use Src\Companies\Domain\ValueObjects\CompanySelectorCollection;

class GetCompaniesSelectorUseCase
{
    public function __construct(private CompanyRepositoryInterface $company_repository)
    {
    }

    public function execute(): CompanySelectorCollection
    {
        return $this->company_repository->selector();
    }
}
