<?php

namespace App\UseCases\Order;

use App\Services\OrderService\Contracts\OrderServiceInterface;
use App\Services\OrderService\DTO\Commands\UpdateOrderServices;
use App\Services\OrderService\DTO\Data\OrderDto;
use App\Services\CatalogOfServices\Contracts\ServiceCatalogInterface;
use Ramsey\Uuid\Uuid;

final readonly class UpdateOrderUseCase
{
    public function __construct(
        private OrderServiceInterface $orderService,
        private ServiceCatalogInterface $serviceCatalog,
    ) {}

    public function execute(string $uuid, array $services, int $userId): OrderDto
    {
        $servicesResult = $this->serviceCatalog->getServicesByUuids(
            $services
        );

        $updateDto = new UpdateOrderServices(
            uuid: Uuid::fromString($uuid),
            userId: $userId,
            services: $servicesResult,
        );

        return $this->orderService->updateOrderServices($updateDto);
    }
}
