<?php

namespace Src\Companies\Infrastructure\Http\Controllers;

use Illuminate\Http\Response;
use Src\Companies\Application\UseCases\PaginateCompaniesUseCase;
use Src\Companies\Infrastructure\Http\Presenters\CompanyResponsePresenter;
use Src\Companies\Infrastructure\Http\Requests\PaginateCompaniesRequest;
use Src\Core\Infrastructure\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Controlador para listado paginado de compañías.
 */
class PaginateCompaniesController extends Controller
{
    /**
     * @param PaginateCompaniesUseCase $paginate_companies_use_case
     * @param CompanyResponsePresenter $company_response_presenter
     */
    public function __construct(
        private PaginateCompaniesUseCase $paginate_companies_use_case,
        private CompanyResponsePresenter $company_response_presenter
    ) {
    }

    /**
     * @param PaginateCompaniesRequest $paginate_companies_request
     * @return Response
     */
    public function paginateCompanies(PaginateCompaniesRequest $paginate_companies_request): Response
    {
        $validated_data = $paginate_companies_request->validated();
        $page = isset($validated_data['page']) ? (int) $validated_data['page'] : 1;
        $per_page = isset($validated_data['per_page']) ? (int) $validated_data['per_page'] : 15;
        $filters = $validated_data;
        unset($filters['page'], $filters['per_page']);

        $paginated_companies = $this->paginate_companies_use_case->execute($page, $per_page, $filters);
        $response_data = $this->company_response_presenter->presentPaginated($paginated_companies);

        return $this->successResponse($response_data);
    }
}
