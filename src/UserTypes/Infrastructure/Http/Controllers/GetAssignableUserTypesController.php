<?php

namespace Src\UserTypes\Infrastructure\Http\Controllers;

use Illuminate\Http\Response;
use Src\Core\Infrastructure\Http\Controllers\Controller;
use Src\UserTypes\Application\UseCases\GetAssignableUserTypesUseCase;
use Src\UserTypes\Infrastructure\Http\Presenters\UserTypeResponsePresenter;

/**
 * Controlador para obtener tipos de asistente asignables por jerarquía.
 */
class GetAssignableUserTypesController extends Controller
{
    /**
     * @param GetAssignableUserTypesUseCase $get_assignable_user_types_use_case
     * @param UserTypeResponsePresenter $user_type_response_presenter
     */
    public function __construct(
        private GetAssignableUserTypesUseCase $get_assignable_user_types_use_case,
        private UserTypeResponsePresenter $user_type_response_presenter
    ) {
    }

    /**
     * @return Response
     */
    public function getAssignableUserTypes(): Response
    {
        $auth_user_type_id = authUserTypeId();
        $user_types = $this->get_assignable_user_types_use_case->execute($auth_user_type_id);
        $response_data = $this->user_type_response_presenter->presentCollection($user_types);

        return $this->successResponse($response_data);
    }
}
