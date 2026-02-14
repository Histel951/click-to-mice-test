<?php

namespace App\Services\OrderService\DTO\Commands;

use App\Services\CatalogOfServices\DTO\Data\ServiceDto;
use Ramsey\Uuid\UuidInterface;

final readonly class UpdateOrderServices
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
