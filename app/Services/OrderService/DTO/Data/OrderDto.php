<?php

namespace App\Services\OrderService\DTO\Data;

use App\Services\OrderService\Enums\OrderStatusEnum;
use Carbon\Carbon;
use Ramsey\Uuid\UuidInterface;

final readonly class OrderDto implements \JsonSerializable
{
    public function __construct(
        private UuidInterface $uuid,
        private OrderStatusEnum $status,
        private int $userId,
        private int $price,
        private int $tax,
        private int $gross,
        private array $externalServicesUuids,
        private Carbon $updatedAt,
        private Carbon $createdAt,
    ) {}

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getStatus(): OrderStatusEnum
    {
        return $this->status;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

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

    /**
     * @return string[]
     */
    public function getExternalServicesUuids(): array
    {
        return $this->externalServicesUuids;
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid->toString(),
            'status' => $this->status->value,
            'userId' => $this->userId,
            'price' => $this->price,
            'tax' => $this->tax,
            'gross' => $this->gross,
            'externalServicesUuids' => $this->externalServicesUuids,
            'createdAt' => $this->createdAt?->toDateTimeString(),
            'updatedAt' => $this->updatedAt?->toDateTimeString(),
        ];
    }
}
