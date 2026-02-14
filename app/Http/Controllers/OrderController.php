<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Order;
use App\UseCases\Order\CreateOrderUseCase;
use App\UseCases\Order\UpdateOrderUseCase;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ApiResponse
    {
        return new ApiResponse(
            data: Order::all(),
            message: 'OK',
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(
        CreateOrderRequest $request,
        CreateOrderUseCase $useCase,
    ): ApiResponse
    {
        $order = $useCase->execute(
            pageId: $request->getPageId(),
            userId: $request->getUserId(),
            services: $request->getServices(),
        );

        return new ApiResponse(
            data: $order,
            message: 'Заказ успешно создан'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateOrderRequest $request,
        UpdateOrderUseCase $useCase
    ): ApiResponse
    {
        $order = $useCase->execute(
            uuid: $request->getUuid(),
            pageId: $request->getPageId(),
            services: $request->getServices(),
            userId: $request->getUserId(),
        );

        return new ApiResponse(
            data: $order,
            message: 'Заказ успешно обновлён'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid): ApiResponse
    {
        Order::destroy($uuid);

        return new ApiResponse(
            data: [],
            message: 'Заказ успешно удалён'
        );
    }
}
