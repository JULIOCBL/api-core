<?php

namespace Src\Companies\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Src\Core\Infrastructure\Traits\HandlesFailedValidation;

class PaginateCompaniesRequest extends FormRequest
{
    use HandlesFailedValidation;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:-1', 'max:100', 'not_in:0'],
            'id' => ['sometimes', 'integer', 'min:1'],
            'status' => ['sometimes', 'boolean'],
            'name' => ['sometimes', 'string', 'max:255'],
            'commercial_name' => ['sometimes', 'string', 'max:255'],
            'bussiness_name' => ['sometimes', 'string', 'max:255'],
            'rfc' => ['sometimes', 'string', 'max:20'],
            'email' => ['sometimes', 'email', 'max:255'],
            'search' => ['sometimes', 'string', 'max:255'],
            'created_from' => ['sometimes', 'date'],
            'created_to' => ['sometimes', 'date'],
        ];
    }
}
