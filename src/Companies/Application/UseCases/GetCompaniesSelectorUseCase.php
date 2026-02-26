<?php

namespace Src\Companies\Application\UseCases;

use Src\Companies\Domain\Contracts\CompanyRepositoryInterface;
use Src\Companies\Domain\ValueObjects\CompanySelectorCollection;

/**
 * Caso de uso para obtener compañías en formato selector.
 */
class GetCompaniesSelectorUseCase
{
    /**
     * @param CompanyRepositoryInterface $company_repository
     */
    public function __construct(private CompanyRepositoryInterface $company_repository)
    {
    }

    /**
     * @return CompanySelectorCollection
     */
    public function execute(): CompanySelectorCollection
    {
        return $this->company_repository->selector();
    }
}
