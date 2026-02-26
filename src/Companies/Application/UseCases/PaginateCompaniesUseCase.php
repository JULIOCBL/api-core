<?php

namespace Src\Companies\Application\UseCases;

use Src\Companies\Domain\Contracts\CompanyRepositoryInterface;
use Src\Companies\Domain\ValueObjects\PaginatedCompanies;

/**
 * Caso de uso para listar compañías paginadas.
 */
class PaginateCompaniesUseCase
{
    /**
     * @param CompanyRepositoryInterface $company_repository
     */
    public function __construct(private CompanyRepositoryInterface $company_repository)
    {
    }

    /**
     * @param int $page
     * @param int $per_page
     * @param array<string, mixed> $filters
     * @return PaginatedCompanies
     */
    public function execute(int $page, int $per_page, array $filters): PaginatedCompanies
    {
        return $this->company_repository->paginate($page, $per_page, $filters);
    }
}
