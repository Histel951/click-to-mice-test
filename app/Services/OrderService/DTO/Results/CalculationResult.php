<?php

namespace App\Services\OrderService\DTO\Results;

final readonly class CalculationResult
{
    public function __construct(
        private int $price,
        private int $tax,
        private int $gross,
    ) {}

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getTax(): int
    {
        return $this->tax;
    }

    public function getGross(): int
    {
        return $this->gross;
    }
}
