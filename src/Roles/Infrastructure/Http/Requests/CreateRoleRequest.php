<?php

namespace Src\Roles\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Src\Core\Infrastructure\Traits\HandlesFailedValidation;

/**
 * Request de validación para crear roles.
 */
class CreateRoleRequest extends FormRequest
{
    use HandlesFailedValidation;

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')
                    ->where('company_id', company())
                    ->whereNull('deleted_at'),
            ],
            'user_type_id' => ['required', 'integer', 'exists:user_types,id'],
            'required_mail' => ['nullable', 'boolean'],
            'status' => ['nullable', 'boolean'],
        ];
    }
}
