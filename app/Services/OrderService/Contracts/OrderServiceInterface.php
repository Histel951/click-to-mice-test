<?php

namespace App\Services\OrderService\Contracts;

use App\Services\OrderService\DTO\Commands\CancelOrder;
use App\Services\OrderService\DTO\Commands\CreateOrder;
use App\Services\OrderService\DTO\Commands\DeleteOrder;
use App\Services\OrderService\DTO\Commands\StartProcessingOrder;
use App\Services\OrderService\DTO\Commands\UpdateOrderServices;
use App\Services\OrderService\DTO\Data\OrderDto;
use App\Services\OrderService\Exceptions\InvalidOrderStatusException;
use App\Services\OrderService\Exceptions\InvalidOrderUserIdException;
use App\Services\OrderService\Exceptions\OrderServicesNotFoundException;

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
     * @param DeleteOrder $deleteOrderDto
     * @return void
     * @throws InvalidOrderUserIdException
     * @throws InvalidOrderStatusException
     */
    public function deleteOrder(DeleteOrder $deleteOrderDto): void;

    /**
     * Регистрация заказа во внешнем сервисе
     *
     * @param StartProcessingOrder $processingOrderDto
     * @return void
     */
    public function startProcessing(StartProcessingOrder $processingOrderDto): void;

    /**
     * Отменить заказ
     *
     * @param CancelOrder $cancelOrderDto
     * @return void
     * @throws InvalidOrderUserIdException
     * @throws InvalidOrderStatusException
     */
    public function cancelOrder(CancelOrder $cancelOrderDto): void;
}
