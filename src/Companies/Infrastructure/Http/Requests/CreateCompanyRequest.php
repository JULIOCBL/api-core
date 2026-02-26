<?php

namespace Src\Companies\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Src\Core\Infrastructure\Traits\HandlesFailedValidation;

/**
 * Request de validación para crear compañía.
 */
class CreateCompanyRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'commercial_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('companies', 'commercial_name')->whereNull('deleted_at'),
            ],
            'bussiness_name' => ['nullable', 'string', 'max:255'],
            'rfc' => ['nullable', 'string', 'max:20'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'primary_color' => ['nullable', 'string', 'max:50'],
            'secondary_color' => ['nullable', 'string', 'max:50'],
            'tertiary_color' => ['nullable', 'string', 'max:50'],
            'image_logo' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'boolean'],
        ];
    }
}
