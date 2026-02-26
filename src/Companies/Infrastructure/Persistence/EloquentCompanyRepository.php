<?php

namespace Src\Companies\Infrastructure\Persistence;

use Src\Companies\Domain\Contracts\CompanyRepositoryInterface;
use Src\Companies\Domain\Entities\Company;
use Src\Companies\Domain\Entities\CompanySelectorItem;
use Src\Companies\Domain\Exceptions\CompanyNotFoundException;
use Src\Companies\Domain\ValueObjects\CompanySelectorCollection;
use Src\Companies\Domain\ValueObjects\CompanyDraft;
use Src\Companies\Domain\ValueObjects\CompanyUpdate;
use Src\Companies\Domain\ValueObjects\PaginatedCompanies;
use Src\Companies\Infrastructure\Persistence\Builders\CompanyDataQueryBuilder;
use Src\Shared\Infrastructure\Persistence\Eloquent\Models\Company as CompanyModel;

/**
 * Adaptador de infraestructura para compañías usando Eloquent.
 */
class EloquentCompanyRepository implements CompanyRepositoryInterface
{
    /**
     * @param CompanyDataQueryBuilder $company_data_query_builder
     */
    public function __construct(private CompanyDataQueryBuilder $company_data_query_builder)
    {
    }

    /**
     * @param CompanyDraft $company_draft
     * @return Company
     */
    public function create(CompanyDraft $company_draft): Company
    {
        $company_model = CompanyModel::query()->create([
            'name' => $company_draft->getName(),
            'commercial_name' => $company_draft->getCommercialName(),
            'bussiness_name' => $company_draft->getBussinessName(),
            'rfc' => $company_draft->getRfc(),
            'contact_phone' => $company_draft->getContactPhone(),
            'email' => $company_draft->getEmail(),
            'primary_color' => $company_draft->getPrimaryColor(),
            'secondary_color' => $company_draft->getSecondaryColor(),
            'tertiary_color' => $company_draft->getTertiaryColor(),
            'image_logo' => $company_draft->getImageLogo(),
            'status' => $company_draft->getStatus(),
        ]);

        return $this->toDomainCompany($company_model);
    }

    /**
     * @param int $company_id
     * @param CompanyUpdate $company_update
     * @return Company
     */
    public function update(int $company_id, CompanyUpdate $company_update): Company
    {
        $company_model = CompanyModel::query()->whereKey($company_id)->whereNull('deleted_at')->first();

        if ($company_model === null) {
            throw new CompanyNotFoundException('Company not found.');
        }

        $company_data = [];

        if ($company_update->hasName()) {
            $company_data['name'] = $company_update->getName();
        }

        if ($company_update->hasCommercialName()) {
            $company_data['commercial_name'] = $company_update->getCommercialName();
        }

        if ($company_update->hasBussinessName()) {
            $company_data['bussiness_name'] = $company_update->getBussinessName();
        }

        if ($company_update->hasRfc()) {
            $company_data['rfc'] = $company_update->getRfc();
        }

        if ($company_update->hasContactPhone()) {
            $company_data['contact_phone'] = $company_update->getContactPhone();
        }

        if ($company_update->hasEmail()) {
            $company_data['email'] = $company_update->getEmail();
        }

        if ($company_update->hasPrimaryColor()) {
            $company_data['primary_color'] = $company_update->getPrimaryColor();
        }

        if ($company_update->hasSecondaryColor()) {
            $company_data['secondary_color'] = $company_update->getSecondaryColor();
        }

        if ($company_update->hasTertiaryColor()) {
            $company_data['tertiary_color'] = $company_update->getTertiaryColor();
        }

        if ($company_update->hasImageLogo()) {
            $company_data['image_logo'] = $company_update->getImageLogo();
        }

        if ($company_update->hasStatus()) {
            $company_data['status'] = $company_update->getStatus();
        }

        $company_model->fill($company_data);
        $company_model->save();

        return $this->toDomainCompany($company_model);
    }

    /**
     * @param int $page
     * @param int $per_page
     * @param array<string, mixed> $filters
     * @return PaginatedCompanies
     */
    public function paginate(int $page, int $per_page, array $filters): PaginatedCompanies
    {
        $paginator = $this->company_data_query_builder->paginate($filters, $page, $per_page);

        $companies = [];

        foreach ($paginator->items() as $company_model) {
            $companies[] = $this->toDomainCompany($company_model);
        }

        return new PaginatedCompanies(
            companies: $companies,
            total: (int) $paginator->total(),
            per_page: (int) $paginator->perPage(),
            current_page: (int) $paginator->currentPage(),
            last_page: (int) $paginator->lastPage(),
            from: $paginator->firstItem(),
            to: $paginator->lastItem(),
            path: $paginator->path(),
            first_page_url: $paginator->url(1),
            last_page_url: $paginator->url($paginator->lastPage()),
            next_page_url: $paginator->nextPageUrl(),
            prev_page_url: $paginator->previousPageUrl()
        );
    }

    /**
     * @return CompanySelectorCollection
     */
    public function selector(): CompanySelectorCollection
    {
        $company_models = $this->company_data_query_builder->selector();

        $items = [];

        foreach ($company_models as $company_model) {
            $items[] = new CompanySelectorItem(
                id: (int) $company_model->id,
                commercial_name: (string) $company_model->commercial_name
            );
        }

        return new CompanySelectorCollection($items);
    }

    /**
     * Mapea un modelo Eloquent a entidad de dominio.
     *
     * @param CompanyModel $company_model
     * @return Company
     */
    private function toDomainCompany(CompanyModel $company_model): Company
    {
        return new Company(
            id: (int) $company_model->id,
            name: (string) $company_model->name,
            commercial_name: (string) $company_model->commercial_name,
            bussiness_name: $company_model->bussiness_name,
            rfc: $company_model->rfc,
            contact_phone: $company_model->contact_phone,
            email: $company_model->email,
            primary_color: $company_model->primary_color,
            secondary_color: $company_model->secondary_color,
            tertiary_color: $company_model->tertiary_color,
            image_logo: $company_model->image_logo,
            status: (bool) $company_model->status
        );
    }
}
