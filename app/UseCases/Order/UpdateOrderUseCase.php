<?php

namespace App\UseCases\Order;

use App\Services\OrderService\Contracts\OrderServiceInterface;
use App\Services\OrderService\DTO\Objects\UpdateOrderServicesDto;
use App\Services\OrderService\DTO\Objects\OrderDto;
use App\Services\ServiceCatalog\Contracts\ServiceCatalogInterface;
use Ramsey\Uuid\Uuid;

final readonly class UpdateOrderUseCase
{
    public function __construct(
        private OrderServiceInterface $orderService,
        private ServiceCatalogInterface $serviceCatalog,
    ) {}

    public function execute(string $uuid, int $pageId, array $services, int $userId): OrderDto
    {
        $servicesResult = $this->serviceCatalog->getServicesByUuids(
            $services,
            $pageId
        );

        $updateDto = new UpdateOrderServicesDto(
            uuid: Uuid::fromString($uuid),
            userId: $userId,
            services: $servicesResult->getServices(),
        );

        return $this->orderService->updateOrderServices($updateDto);
    }
}
