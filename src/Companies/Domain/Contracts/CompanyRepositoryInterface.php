<?php

namespace Src\Companies\Domain\Contracts;

use Src\Companies\Domain\Entities\Company;
use Src\Companies\Domain\ValueObjects\CompanyDraft;
use Src\Companies\Domain\ValueObjects\CompanySelectorCollection;
use Src\Companies\Domain\ValueObjects\CompanyUpdate;
use Src\Companies\Domain\ValueObjects\PaginatedCompanies;

interface CompanyRepositoryInterface
{
    public function create(CompanyDraft $company_draft): Company;
    public function update(int $company_id, CompanyUpdate $company_update): Company;
    public function paginate(int $page, int $per_page, array $filters): PaginatedCompanies;
    public function selector(): CompanySelectorCollection;
}
