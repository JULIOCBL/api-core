<?php

namespace Src\Companies\Infrastructure\Http\Presenters;

use Src\Companies\Domain\Entities\Company;
use Src\Companies\Domain\Entities\CompanySelectorItem;
use Src\Companies\Domain\ValueObjects\CompanySelectorCollection;
use Src\Companies\Domain\ValueObjects\PaginatedCompanies;

/**
 * Presenter para convertir objetos de dominio de compañías
 * a estructuras de respuesta HTTP.
 */
class CompanyResponsePresenter
{
    /**
     * @param Company $company
     * @return array<string, mixed>
     */
    public function present(Company $company): array
    {
        return [
            'id' => $company->getId(),
            'name' => $company->getName(),
            'commercial_name' => $company->getCommercialName(),
            'bussiness_name' => $company->getBussinessName(),
            'rfc' => $company->getRfc(),
            'contact_phone' => $company->getContactPhone(),
            'email' => $company->getEmail(),
            'primary_color' => $company->getPrimaryColor(),
            'secondary_color' => $company->getSecondaryColor(),
            'tertiary_color' => $company->getTertiaryColor(),
            'image_logo' => $company->getImageLogo(),
            'status' => $company->getStatus(),
        ];
    }

    /**
     * @param PaginatedCompanies $paginated_companies
     * @return array<string, mixed>
     */
    public function presentPaginated(PaginatedCompanies $paginated_companies): array
    {
        $items = [];

        foreach ($paginated_companies->getCompanies() as $company) {
            $items[] = $this->present($company);
        }

        $meta_links = [];
        for ($page = 1; $page <= $paginated_companies->getLastPage(); $page++) {
            $meta_links[] = [
                'url' => $paginated_companies->getPath() . '?page=' . $page,
                'label' => (string) $page,
                'active' => $page === $paginated_companies->getCurrentPage(),
            ];
        }

        return [
            'data' => $items,
            'links' => [
                'first' => $paginated_companies->getFirstPageUrl(),
                'last' => $paginated_companies->getLastPageUrl(),
                'prev' => $paginated_companies->getPrevPageUrl(),
                'next' => $paginated_companies->getNextPageUrl(),
            ],
            'meta' => [
                'current_page' => $paginated_companies->getCurrentPage(),
                'from' => $paginated_companies->getFrom(),
                'last_page' => $paginated_companies->getLastPage(),
                'links' => $meta_links,
                'path' => $paginated_companies->getPath(),
                'per_page' => $paginated_companies->getPerPage(),
                'to' => $paginated_companies->getTo(),
                'total' => $paginated_companies->getTotal(),
            ],
        ];
    }

    /**
     * @param CompanySelectorCollection $company_selector_collection
     * @return array<int, array<string, mixed>>
     */
    public function presentSelector(CompanySelectorCollection $company_selector_collection): array
    {
        $items = [];

        foreach ($company_selector_collection->getItems() as $company_selector_item) {
            $items[] = $this->presentSelectorItem($company_selector_item);
        }

        return $items;
    }

    /**
     * @param CompanySelectorItem $company_selector_item
     * @return array<string, int|string>
     */
    private function presentSelectorItem(CompanySelectorItem $company_selector_item): array
    {
        return [
            'id' => $company_selector_item->getId(),
            'commercial_name' => $company_selector_item->getCommercialName(),
        ];
    }
}
