<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

final class ServiceListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
        ];
    }

    public function getPage(): int
    {
        return (int) $this->validated()['page'] ?? 1;
    }
}
