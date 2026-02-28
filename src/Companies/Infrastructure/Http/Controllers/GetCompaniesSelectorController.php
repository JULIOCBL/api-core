<?php

namespace Src\Companies\Infrastructure\Http\Controllers;

use Illuminate\Http\Response;
use Src\Companies\Application\UseCases\GetCompaniesSelectorUseCase;
use Src\Companies\Infrastructure\Http\Presenters\CompanyResponsePresenter;
use Src\Core\Infrastructure\Http\Controllers\Controller;

/**
 * Controlador para selector de compañías.
 */
class GetCompaniesSelectorController extends Controller
{
    /**
     * @param GetCompaniesSelectorUseCase $get_companies_selector_use_case
     * @param CompanyResponsePresenter $company_response_presenter
     */
    public function __construct(
        private GetCompaniesSelectorUseCase $get_companies_selector_use_case,
        private CompanyResponsePresenter $company_response_presenter
    ) {
    }

    /**
     * @return Response
     */
    public function getCompaniesSelector(): Response
    {

        $selector_companies = $this->get_companies_selector_use_case->execute();
        $response_data = $this->company_response_presenter->presentSelector($selector_companies);

        return $this->successResponse($response_data);
    }
}
