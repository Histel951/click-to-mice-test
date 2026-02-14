<?php

namespace App\Services\OrderService\DTO\Commands;

use Ramsey\Uuid\UuidInterface;

final readonly class StartProcessingOrder
{
    public function __construct(
        private UuidInterface $uuid,
        private int $userId,
    ) {}

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
