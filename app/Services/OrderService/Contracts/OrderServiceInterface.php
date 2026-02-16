<?php

namespace App\Services\OrderService\Contracts;

use App\Services\OrderService\DTO\Commands\CreateOrder;
use App\Services\OrderService\DTO\Commands\UpdateOrderServices;
use App\Services\OrderService\DTO\Data\OrderDto;
use App\Services\OrderService\Exceptions\InvalidOrderStatusException;
use App\Services\OrderService\Exceptions\InvalidOrderUserIdException;
use App\Services\OrderService\Exceptions\OrderServicesNotFoundException;
use Ramsey\Uuid\UuidInterface;

interface OrderServiceInterface
{
    /**
     * Создание заказа
     *
     * @param CreateOrder $createOrderDto
     * @return OrderDto
     * @throws OrderServicesNotFoundException
     */
    public function createOrder(CreateOrder $createOrderDto): OrderDto;

    /**
     * Обновление услуг заказа
     *
     * @param UpdateOrderServices $updateDto
     * @return OrderDto
     * @throws OrderServicesNotFoundException
     * @throws InvalidOrderUserIdException
     * @throws InvalidOrderStatusException
     */
    public function updateOrderServices(UpdateOrderServices $updateDto): OrderDto;

    /**
     * Удалить заказ
     *
     * @param UuidInterface $uuid
     * @param int $userId
     * @return void
     * @throws InvalidOrderUserIdException
     * @throws InvalidOrderStatusException
     */
    public function deleteOrder(UuidInterface $uuid, int $userId): void;

    /**
     * Регистрация заказа во внешнем сервисе
     *
     * @param UuidInterface $uuid
     * @param int $userId
     * @return void
     */
    public function startProcessing(UuidInterface $uuid, int $userId): void;

    /**
     * Отменить заказ
     *
     * @param UuidInterface $uuid
     * @param int $userId
     * @return void
     * @throws InvalidOrderUserIdException
     * @throws InvalidOrderStatusException
     */
    public function cancelOrder(UuidInterface $uuid, int $userId): void;
}
