<?php

namespace Src\Companies\Application\UseCases;

use Src\Companies\Domain\Contracts\CompanyRepositoryInterface;
use Src\Companies\Domain\ValueObjects\PaginatedCompanies;

class PaginateCompaniesUseCase
{
    public function __construct(private CompanyRepositoryInterface $company_repository)
    {
    }

    public function execute(int $page, int $per_page, array $filters): PaginatedCompanies
    {
        return $this->company_repository->paginate($page, $per_page, $filters);
    }
}
