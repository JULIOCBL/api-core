<?php

namespace Src\Companies\Infrastructure\Http\Controllers;

use Illuminate\Http\Response;
use Src\Companies\Application\DataTransferObjects\UpdateCompanyInput;
use Src\Companies\Application\UseCases\UpdateCompanyUseCase;
use Src\Companies\Domain\Exceptions\CompanyNotFoundException;
use Src\Companies\Infrastructure\Http\Presenters\CompanyResponsePresenter;
use Src\Companies\Infrastructure\Http\Requests\UpdateCompanyRequest;
use Src\Core\Infrastructure\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Controlador para actualización de compañías.
 */
class UpdateCompanyController extends Controller
{
    /**
     * @param UpdateCompanyUseCase $update_company_use_case
     * @param CompanyResponsePresenter $company_response_presenter
     */
    public function __construct(
        private UpdateCompanyUseCase $update_company_use_case,
        private CompanyResponsePresenter $company_response_presenter
    ) {
    }

    /**
     * @param UpdateCompanyRequest $update_company_request
     * @param int $company_id
     * @return Response
     */
    public function updateCompany(UpdateCompanyRequest $update_company_request, int $company_id): Response
    {
        $update_company_input = UpdateCompanyInput::fromArray($company_id, $update_company_request->validated());

        try {
            $company = $this->update_company_use_case->execute($update_company_input);
        } catch (CompanyNotFoundException $company_not_found_exception) {
            return $this->errorsMessage(
                [
                    'status' => HttpResponse::HTTP_NOT_FOUND,
                    'title' => 'Company not found',
                    'message' => 'The company does not exist or is deleted.',
                ],
                HttpResponse::HTTP_NOT_FOUND
            );
        }

        $response_data = $this->company_response_presenter->present($company);

        return $this->successResponse(
            $response_data,
            [
                'title' => 'Company updated',
                'description' => 'The company was updated successfully',
            ],
            HttpResponse::HTTP_OK
        );
    }
}
