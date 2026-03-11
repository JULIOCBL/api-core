<?php

namespace Src\Companies\Application\UseCases;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Companies\Domain\Contracts\CompanyRepositoryInterface;

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
     * @return LengthAwarePaginator
     */
    public function execute(int $page, int $per_page, array $filters): LengthAwarePaginator
    {
        return $this->company_repository->paginate($page, $per_page, $filters);
    }
}
