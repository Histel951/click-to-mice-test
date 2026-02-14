<?php
declare(strict_types=1);

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

final class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

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
     * Нормализуем входные данные
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->services)) {
            $this->merge([
                'services' => explode(',', $this->services),
            ]);
        }
    }

    /**
     * @return string[]
     */
    public function getServices(): array
    {
        return $this->input('services');
    }
}
