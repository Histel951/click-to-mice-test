<?php

namespace App\UseCases\Order;

use App\Services\OrderService\Contracts\OrderServiceInterface;
use App\Services\OrderService\DTO\Commands\CreateOrder;
use App\Services\OrderService\DTO\Data\OrderDto;
use App\Services\CatalogOfServices\Contracts\ServiceCatalogInterface;
use Ramsey\Uuid\Uuid;

final readonly class CreateOrderUseCase
{
    public function __construct(
        private OrderServiceInterface $orderService,
        private ServiceCatalogInterface $serviceCatalog
    ) {}

    public function execute(int $userId, array $services): OrderDto
    {
        $servicesResult = $this->serviceCatalog->getServicesByUuids(
            $services
        );

        $createOrderDto = new CreateOrder(
            uuid: Uuid::uuid4(),
            userId: $userId,
            services: $servicesResult,
        );

        return $this->orderService->createOrder($createOrderDto);
    }
}
