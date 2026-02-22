<?php

namespace Src\Companies\Infrastructure\Http\Controllers;

use Src\Companies\Application\DataTransferObjects\CreateCompanyInput;
use Src\Companies\Application\UseCases\CreateCompanyUseCase;
use Src\Companies\Infrastructure\Http\Presenters\CompanyResponsePresenter;
use Illuminate\Http\Response;
use Src\Companies\Infrastructure\Http\Requests\CreateCompanyRequest;
use Src\Core\Infrastructure\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class CreateCompanyController extends Controller
{
    public function __construct(
        private CreateCompanyUseCase $create_company_use_case,
        private CompanyResponsePresenter $company_response_presenter
    ) {
    }

    public function createCompany(CreateCompanyRequest $create_company_request): Response
    {
        $create_company_input = CreateCompanyInput::fromArray($create_company_request->validated());
        $company = $this->create_company_use_case->execute($create_company_input);
        $response_data = $this->company_response_presenter->present($company);

        return $this->successResponse(
            $response_data,
            [
                'title' => 'Company created',
                'description' => 'The company was created successfully',
            ],
            HttpResponse::HTTP_CREATED
        );
    }
}
