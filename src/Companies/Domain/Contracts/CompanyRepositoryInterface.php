<?php

namespace Src\Companies\Domain\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Companies\Domain\Entities\Company;
use Src\Companies\Domain\ValueObjects\CompanyDraft;
use Src\Companies\Domain\ValueObjects\CompanySelectorCollection;
use Src\Companies\Domain\ValueObjects\CompanyUpdate;

/**
 * Puerto de salida para persistencia y consulta de compañías.
 */
interface CompanyRepositoryInterface
{
    /**
     * @param CompanyDraft $company_draft
     * @return Company
     */
    public function create(CompanyDraft $company_draft): Company;

    /**
     * @param int $company_id
     * @param CompanyUpdate $company_update
     * @return Company
     */
    public function update(int $company_id, CompanyUpdate $company_update): Company;

    /**
     * @param int $page
     * @param int $per_page
     * @param array<string, mixed> $filters
     * @return LengthAwarePaginator
     */
    public function paginate(int $page, int $per_page, array $filters): LengthAwarePaginator;

    /**
     * @return CompanySelectorCollection
     */
    public function selector(): CompanySelectorCollection;
}
