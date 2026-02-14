<?php

namespace App\Services\OrderService\DTO\Commands;

final readonly class RegisterOrder
{
    public function __construct(
        private array $services,
        private float $price,
        private float $tax,
        private float $gross,
    ) {}

    public function getServices(): array
    {
        return $this->services;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getTax(): float
    {
        return $this->tax;
    }

    public function getGross(): float
    {
        return $this->gross;
    }
}
