<?php

namespace Src\Roles\Infrastructure\Http\Controllers;

use Illuminate\Http\Response;
use Src\Core\Infrastructure\Http\Controllers\Controller;
use Src\Roles\Application\DataTransferObjects\CreateRoleInput;
use Src\Roles\Application\UseCases\CreateRoleUseCase;
use Src\Roles\Infrastructure\Http\Presenters\RoleResponsePresenter;
use Src\Roles\Infrastructure\Http\Requests\CreateRoleRequest;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Controlador para creación de roles.
 */
class CreateRoleController extends Controller
{
    /**
     * @param CreateRoleUseCase $create_role_use_case
     * @param RoleResponsePresenter $role_response_presenter
     */
    public function __construct(
        private CreateRoleUseCase $create_role_use_case,
        private RoleResponsePresenter $role_response_presenter
    ) {
    }

    /**
     * @param CreateRoleRequest $create_role_request
     * @return Response
     */
    public function createRole(CreateRoleRequest $create_role_request): Response
    {
        $create_role_input = CreateRoleInput::fromArray($create_role_request->validated(), company());
        $role = $this->create_role_use_case->execute($create_role_input);
        $response_data = $this->role_response_presenter->present($role);

        return $this->successResponse(
            $response_data,
            [
                'title' => 'Role created',
                'description' => 'The role was created successfully',
            ],
            HttpResponse::HTTP_CREATED
        );
    }
}
