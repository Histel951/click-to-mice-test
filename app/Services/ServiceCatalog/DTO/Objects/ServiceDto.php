<?php

namespace App\Services\ServiceCatalog\DTO\Objects;

use Carbon\Carbon;
use Ramsey\Uuid\UuidInterface;

final readonly class ServiceDto implements \JsonSerializable
{
    public function __construct(
        private UuidInterface $uuid,
        private string $name,
        private string $description,
        private float $price,
        private float $tax,
        private float $gross,
        private string $image,
        private Carbon $updatedAt,
    ) {}

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
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

    public function getImage(): string
    {
        return $this->image;
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid->toString(),
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'tax' => $this->tax,
            'gross' => $this->gross,
            'image' => $this->image,
        ];
    }
}
