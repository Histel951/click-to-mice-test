<?php

declare(strict_types=1);

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateServicesOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'uuid' => [
                'required',
                'uuid'
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

    public function validationData(): array
    {
        return array_merge($this->all(), [
            'uuid' => $this->route('uuid'),
        ]);
    }

    /**
     * @return string[]
     */
    public function getServices(): array
    {
        return $this->input('services');
    }
}
