<?php

namespace App\Services\OrderService\DTO\Objects;

use App\Services\ServiceCatalog\DTO\Objects\ServiceDto;
use Ramsey\Uuid\UuidInterface;

final readonly class UpdateOrderServicesDto
{
    public function __construct(
        private UuidInterface $uuid,
        private int $userId,
        private array $services,
    ) {}

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return ServiceDto[]
     */
    public function getExternalServices(): array
    {
        return $this->services;
    }
}
