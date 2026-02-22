<?php

namespace Src\Companies\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Src\Core\Infrastructure\Traits\HandlesFailedValidation;

class UpdateCompanyRequest extends FormRequest
{
    use HandlesFailedValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $company_id = (int) $this->route('company_id');

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'commercial_name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('companies', 'commercial_name')
                    ->ignore($company_id, 'id')
                    ->whereNull('deleted_at'),
            ],
            'bussiness_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'rfc' => ['sometimes', 'nullable', 'string', 'max:20'],
            'contact_phone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'primary_color' => ['sometimes', 'nullable', 'string', 'max:50'],
            'secondary_color' => ['sometimes', 'nullable', 'string', 'max:50'],
            'tertiary_color' => ['sometimes', 'nullable', 'string', 'max:50'],
            'image_logo' => ['sometimes', 'nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
