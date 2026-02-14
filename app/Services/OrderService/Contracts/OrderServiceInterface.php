<?php

namespace App\Services\OrderService\Contracts;

use App\Services\OrderService\DTO\Objects\CreateOrderDto;
use App\Services\OrderService\DTO\Objects\UpdateOrderServicesDto;
use App\Services\OrderService\DTO\Objects\OrderDto;
use App\Services\OrderService\Exceptions\InvalidOrderStatusForUpdateException;
use App\Services\OrderService\Exceptions\OrderServicesNotFoundException;

interface OrderServiceInterface
{
    /**
     * Создание заказа
     *
     * @param CreateOrderDto $createOrderDto
     * @return OrderDto
     * @throws OrderServicesNotFoundException
     */
    public function createOrder(CreateOrderDto $createOrderDto): OrderDto;

    /**
     * Обновление заказа
     *
     * @param UpdateOrderServicesDto $updateDto
     * @return OrderDto
     * @throws OrderServicesNotFoundException
     * @throws InvalidOrderStatusForUpdateException
     */
    public function updateOrderServices(UpdateOrderServicesDto $updateDto): OrderDto;
}
