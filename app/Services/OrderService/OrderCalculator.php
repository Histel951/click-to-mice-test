<?php

namespace App\Services\OrderService;

use App\Services\OrderService\DTO\Results\CalculationResult;
use App\Services\ServiceCatalog\DTO\Objects\ServiceDto;

final class OrderCalculator
{
    /**
     * @param ServiceDto[] $services
     */
    public function calculate(array $services): CalculationResult
    {
        $price = array_sum(array_map(fn($s) => $s->getPrice(), $services));
        $tax   = array_sum(array_map(fn($s) => $s->getTax(), $services));
        $gross = array_sum(array_map(fn($s) => $s->getGross(), $services));

        return new CalculationResult(
            price: $price,
            tax: $tax,
            gross: $gross
        );
    }
}
