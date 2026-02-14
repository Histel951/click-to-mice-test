<?php

declare(strict_types=1);

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'userId' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'uuid' => [
                'required',
                'uuid',
            ],

            'servicesPageId' => [
                'required',
                'integer',
                'min:1',
            ],

            'services' => [
                'required',
                'array',
                'min:1',
            ],

            'services.*' => [
                'required',
                'uuid',
                'distinct',
            ],
        ];
    }

    /**
     * Нормализация входных данных
     * (если services передали строкой через query ?services=a,b,c)
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->services)) {
            $this->merge([
                'services' => explode(',', $this->services),
            ]);
        }
    }

    public function getUserId(): int
    {
        return (int) $this->input('userId');
    }

    public function getUuid(): string
    {
        return $this->input('uuid');
    }

    public function getPageId(): int
    {
        return (int) $this->input('servicesPageId');
    }

    /**
     * @return string[]
     */
    public function getServices(): array
    {
        return $this->input('services');
    }
}
