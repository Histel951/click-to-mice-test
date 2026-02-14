<?php

namespace App\Services\OrderService;

use App\Models\Order;
use App\Services\OrderService\Contracts\OrderServiceInterface;
use App\Services\OrderService\DTO\Commands\CancelOrder;
use App\Services\OrderService\DTO\Commands\CreateOrder;
use App\Services\OrderService\DTO\Commands\DeleteOrder;
use App\Services\OrderService\DTO\Commands\RegisterOrder;
use App\Services\OrderService\DTO\Commands\StartProcessingOrder;
use App\Services\OrderService\DTO\Commands\UpdateOrderServices;
use App\Services\OrderService\DTO\Data\OrderDto;
use App\Services\OrderService\Enums\OrderStatusEnum;
use App\Services\OrderService\Exceptions\InvalidOrderStatusException;
use App\Services\OrderService\Exceptions\InvalidOrderUserIdException;
use App\Services\OrderService\Exceptions\OrderServicesNotFoundException;
use App\Services\OrderService\Transports\OrderHttpClient;
use App\Services\CatalogOfServices\DTO\Data\ServiceDto;
use Ramsey\Uuid\UuidInterface;

final readonly class OrderService implements OrderServiceInterface
{
    public function __construct(
        private OrderCalculator $calculator,
        private OrderHttpClient $client,
    ) {}

    public function createOrder(CreateOrder $createOrderDto): OrderDto
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

    public function updateOrderServices(UpdateOrderServices $updateDto): OrderDto
    {
        if (empty($updateDto->getExternalServices())) {
            throw new OrderServicesNotFoundException('Order must contain at least one service');
        }

        $order = $this->getOrderByUuid($updateDto->getUuid());

        $this->modifyChecks($order, $updateDto->getUserId());

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
            status: $order->status,
            userId: $order->user_id,
            price: $calculation->getPrice(),
            tax: $calculation->getTax(),
            gross: $calculation->getGross(),
            externalServicesUuids: $externalServicesUuids,
            updatedAt: $order->updated_at,
            createdAt: $order->created_at,
        );
    }

    public function deleteOrder(DeleteOrder $deleteOrderDto): void
    {
        $order = $this->getOrderByUuid($deleteOrderDto->getUuid());

        $this->modifyChecks($order, $deleteOrderDto->getUserId());

        $order->delete();
    }

    public function startProcessing(StartProcessingOrder $processingOrderDto): void
    {
        $order = $this->getOrderByUuid($processingOrderDto->getUuid());

        $this->modifyChecks($order, $processingOrderDto->getUserId());

        $externalUuid = $this->client->registerOrder(new RegisterOrder(
            services: $order->external_services_uuids,
            price: $order->price,
            tax: $order->tax,
            gross: $order->gross,
        ));

        $order->update([
            'status' => OrderStatusEnum::IN_PROCESSING,
            'external_uuid' => $externalUuid,
        ]);
    }

    public function cancelOrder(CancelOrder $cancelOrderDto): void
    {
        $order = $this->getOrderByUuid($cancelOrderDto->getUuid());

        if ($order->user_id !== $cancelOrderDto->getUserId()) {
            throw new InvalidOrderUserIdException('The user can only cancel their own orders');
        }

        if ($order->status !== OrderStatusEnum::IN_PROCESSING) {
            throw new InvalidOrderStatusException('Only in processing orders can be cancelled');
        }

        $order->update([
            'status' => OrderStatusEnum::CANCELLED,
        ]);
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

    /**
     * Проверки, может ли пользователь изменять заказ
     *
     * @param Order $order
     * @param int $userId
     * @return void
     */
    private function modifyChecks(Order $order, int $userId): void
    {
        if ($order->user_id !== $userId) {
            throw new InvalidOrderUserIdException('The user can only change their own orders');
        }

        if ($order->status !== OrderStatusEnum::DRAFT) {
            throw new InvalidOrderStatusException('Only draft orders can be modified');
        }
    }

    private function getOrderByUuid(UuidInterface $uuid): Order
    {
        return Order::query()
            ->where('uuid', $uuid->toString())
            ->firstOrFail();
    }
}
