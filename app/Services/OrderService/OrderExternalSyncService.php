<?php
declare(strict_types = 1);

namespace App\Services\OrderService;

use App\Models\Order;
use App\Services\OrderService\Enums\OrderExternalStatusEnum;
use App\Services\OrderService\Enums\OrderStatusEnum;
use App\Services\OrderService\Transports\OrderHttpClient;

final readonly class OrderExternalSyncService
{
    public function __construct(
        private OrderHttpClient $client,
    ) {}

    /**
     * Синхронизует статусы заказов в локальной базе и внешнем сервисе
     *
     * @return void
     */
    public function sync(): void
    {
        Order::whereNotNull('external_uuid')
            ->where('status', OrderStatusEnum::IN_PROCESSING->value)
            ->chunkById(100, function ($orders) {
                // chunk поскольку есть внешний http вызов, он лёгкий, поэтому проблем быть не должно
                // Но при высоких нагрузках, лучше избегать таких http запросов в циклах
                // chunk это как самый быстрый в реализации и простой вариант
                foreach ($orders as $order) {
                    try {
                        $externalStatus = $this->client
                            ->getExternalOrderStatus($order->external_uuid);

                        if ($externalStatus === OrderExternalStatusEnum::DONE) {
                            $order->update([
                                'status' => OrderStatusEnum::DONE->value,
                            ]);
                        }
                    } catch (\Throwable $exception) {
                        report($exception);
                    }
                }
            }, 'uuid');
    }
}
