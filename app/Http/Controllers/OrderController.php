<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\CancelOrderRequest;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\DestroyOrderRequest;
use App\Http\Requests\Order\StartProcessingOrderRequest;
use App\Http\Requests\Order\UpdateServicesOrderRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Order;
use App\Services\OrderService\Contracts\OrderServiceInterface;
use App\Services\OrderService\DTO\Commands\CancelOrder;
use App\Services\OrderService\DTO\Commands\DeleteOrder;
use App\Services\OrderService\DTO\Commands\StartProcessingOrder;
use App\UseCases\Order\CreateOrderUseCase;
use App\UseCases\Order\UpdateOrderUseCase;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class OrderController extends Controller
{
    public function index(Request $request): ApiResponse
    {
        return new ApiResponse(
            data: Order::where('user_id', $request->user()->getKey())->get(),
            message: 'OK',
        );
    }

    public function show(Order $order): ApiResponse
    {
        return new ApiResponse(
            data: $order,
            message: 'OK',
        );
    }

    public function create(
        CreateOrderRequest $request,
        CreateOrderUseCase $useCase,
    ): ApiResponse
    {
        $order = $useCase->execute(
            userId: $request->user()->getKey(),
            services: $request->getServices(),
        );

        return new ApiResponse(
            data: $order,
            message: 'Заказ успешно создан',
            status: 201
        );
    }

    public function updateServices(
        string                     $uuid,
        UpdateServicesOrderRequest $request,
        UpdateOrderUseCase         $useCase
    ): ApiResponse
    {
        $order = $useCase->execute(
            uuid: Uuid::fromString($uuid),
            services: $request->getServices(),
            userId: $request->user()->getKey(),
        );

        return new ApiResponse(
            data: $order,
            message: 'Заказ успешно обновлён'
        );
    }

    public function startProcessing(
        string                      $uuid,
        StartProcessingOrderRequest $request,
        OrderServiceInterface       $orderService
    ): ApiResponse
    {
        $orderService->startProcessing(new StartProcessingOrder(
            uuid: Uuid::fromString($uuid),
            userId: $request->user()->getKey(),
        ));

        return new ApiResponse(
            data: [
                'result' => true,
            ],
            message: 'Заказ успешно отправлен на обработку'
        );
    }

    public function destroy(
        string                $uuid,
        DestroyOrderRequest   $request,
        OrderServiceInterface $orderService
    ): ApiResponse
    {
        $orderService->deleteOrder(new DeleteOrder(
            uuid: Uuid::fromString($uuid),
            userId: $request->user()->getKey(),
        ));

        return new ApiResponse(
            data: [
                'result' => true,
            ],
            message: 'Заказ успешно удалён'
        );
    }

    public function cancel(
        string                $uuid,
        CancelOrderRequest    $request,
        OrderServiceInterface $orderService
    ): ApiResponse
    {
        $orderService->cancelOrder(new CancelOrder(
            uuid: Uuid::fromString($uuid),
            userId: $request->user()->getKey(),
        ));

        return new ApiResponse(
            data: [
                'result' => true,
            ],
            message: 'Заказ успешно отменён',
        );
    }
}
