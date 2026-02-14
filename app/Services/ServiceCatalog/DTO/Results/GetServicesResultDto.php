<?php

namespace App\Services\ServiceCatalog\DTO\Results;

use App\Services\ServiceCatalog\DTO\Objects\ServiceDto;

final readonly class GetServicesResultDto
{
    public function __construct(
        private array $services,
    ) {}

    /**
     * @return ServiceDto[]
     */
    public function getServices(): array
    {
        return $this->services;
    }
}
