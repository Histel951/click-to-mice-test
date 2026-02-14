<?php

namespace App\Services\OrderService;

use App\Models\Order;
use App\Services\OrderService\Contracts\OrderServiceInterface;
use App\Services\OrderService\DTO\Objects\CreateOrderDto;
use App\Services\OrderService\DTO\Objects\UpdateOrderServicesDto;
use App\Services\OrderService\DTO\Objects\OrderDto;
use App\Services\OrderService\Enums\OrderStatusEnum;
use App\Services\OrderService\Exceptions\InvalidOrderStatusForUpdateException;
use App\Services\OrderService\Exceptions\OrderServicesNotFoundException;
use App\Services\ServiceCatalog\DTO\Objects\ServiceDto;

final readonly class OrderService implements OrderServiceInterface
{
    public function __construct(
        private OrderCalculator $calculator,
    ) {}

    public function createOrder(CreateOrderDto $createOrderDto): OrderDto
    {
        if (empty($createOrderDto->getExternalServices())) {
            throw new OrderServicesNotFoundException('Order must contain at least one service');
        }

        $calculation = $this->calculator->calculate($createOrderDto->getExternalServices());

        $externalServicesUuids = $this->getExternalServicesUuids($createOrderDto->getExternalServices());

        $order = Order::create([
            'uuid' => $createOrderDto->getUuid()->toString(),
            'user_id' => $createOrderDto->getUserId(),
            'status' => OrderStatusEnum::DRAFT->value,
            'price' => $calculation->getPrice(),
            'tax' => $calculation->getTax(),
            'gross' => $calculation->getGross(),
            'external_services_uuids' => $externalServicesUuids
        ]);

        return new OrderDto(
            uuid: $createOrderDto->getUuid(),
            status: OrderStatusEnum::DRAFT,
            userId: $createOrderDto->getUserId(),
            price: $calculation->getPrice(),
            tax: $calculation->getTax(),
            gross: $calculation->getGross(),
            externalServicesUuids: $externalServicesUuids,
            updatedAt: $order->updated_at,
            createdAt: $order->created_at
        );
    }

    public function updateOrderServices(UpdateOrderServicesDto $updateDto): OrderDto
    {
        if (empty($updateDto->getExternalServices())) {
            throw new OrderServicesNotFoundException('Order must contain at least one service');
        }

        $order = Order::query()
            ->where('uuid', $updateDto->getUuid()->toString())
            ->firstOrFail();

        if ($order->user_id !== $updateDto->getUserId()) {
            throw new InvalidOrderStatusForUpdateException('The user can only change their own orders');
        }

        if ($order->status !== OrderStatusEnum::DRAFT) {
            throw new InvalidOrderStatusForUpdateException('Only draft orders can be modified');
        }

        $calculation = $this->calculator->calculate($updateDto->getExternalServices());

        $externalServicesUuids = $this->getExternalServicesUuids($updateDto->getExternalServices());

        $order->update([
            'price' => $calculation->getPrice(),
            'tax' => $calculation->getTax(),
            'gross' => $calculation->getGross(),
            'external_services_uuids' => $externalServicesUuids,
        ]);

        return new OrderDto(
            uuid: $updateDto->getUuid(),
            status: OrderStatusEnum::DRAFT,
            userId: $order->user_id,
            price: $calculation->getPrice(),
            tax: $calculation->getTax(),
            gross: $calculation->getGross(),
            externalServicesUuids: $externalServicesUuids,
            updatedAt: $order->updated_at,
            createdAt: $order->created_at,
        );
    }

    /**
     * @param ServiceDto[] $externalServices
     * @return array
     */
    private function getExternalServicesUuids(array $externalServices): array
    {
        return array_map(
            fn($service) => $service->getUuid(), $externalServices
        );
    }
}
