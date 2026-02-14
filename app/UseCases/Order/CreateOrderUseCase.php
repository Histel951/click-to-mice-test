<?php

namespace App\UseCases\Order;

use App\Services\OrderService\Contracts\OrderServiceInterface;
use App\Services\OrderService\DTO\Objects\CreateOrderDto;
use App\Services\OrderService\DTO\Objects\OrderDto;
use App\Services\ServiceCatalog\Contracts\ServiceCatalogInterface;
use Ramsey\Uuid\Uuid;

final readonly class CreateOrderUseCase
{
    public function __construct(
        private OrderServiceInterface $orderService,
        private ServiceCatalogInterface $serviceCatalog
    ) {}

    public function execute(int $pageId, int $userId, array $services): OrderDto
    {
        $servicesResult = $this->serviceCatalog->getServicesByUuids(
            $services,
            $pageId
        );

        $createOrderDto = new CreateOrderDto(
            uuid: Uuid::uuid4(),
            userId: $userId,
            services: $servicesResult->getServices(),
        );

        return $this->orderService->createOrder($createOrderDto);
    }
}
