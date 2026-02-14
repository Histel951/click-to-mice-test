<?php
declare(strict_types = 1);

namespace App\Services\OrderService\Transports;

use App\Services\OrderService\DTO\Commands\RegisterOrder;
use App\Services\OrderService\Enums\OrderExternalStatusEnum;
use App\Services\OrderService\Exceptions\OrderConnectionException;
use App\Services\OrderService\Exceptions\OrderRegisterInvalidExternalException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

final readonly class OrderHttpClient
{
    private const API_ORDER = '/api/order';

    public function __construct(
        private string $url,
        private string $token,
    ) {}

    /**
     * Регистрирует заказ во внешнем сервисе, возвращает external_uuid
     *
     * @param RegisterOrder $registerOrderDto
     * @return string
     */
    public function registerOrder(RegisterOrder $registerOrderDto): string
    {
        try {
            $response = Http::withToken($this->token)
                ->post($this->url . self::API_ORDER, [
                    'services' => $registerOrderDto->getServices(),
                    'price' => $registerOrderDto->getPrice(),
                    'tax' => $registerOrderDto->getTax(),
                    'gross' => $registerOrderDto->getGross()
                ]);
        } catch (ConnectionException $e) {
            throw new OrderConnectionException('Could not connect to external service', 0, $e);
        }

        $externalUuid = $response->json('uuid');
        if (!is_string($externalUuid)) {
            throw new OrderRegisterInvalidExternalException(
                'Invalid response from external service: expected string'
            );
        }

        return $externalUuid;
    }

    /**
     * Получаем статус заказа с внешнего сервиса
     *
     * @param string $externalUuid
     * @return OrderExternalStatusEnum
     */
    public function getExternalOrderStatus(string $externalUuid): OrderExternalStatusEnum
    {
        try {
            $response = Http::withToken($this->token)
                ->get($this->url . self::API_ORDER . '/' . $externalUuid);
        } catch (ConnectionException $e) {
            throw new OrderConnectionException('Could not connect to external service', 0, $e);
        }

        $state = $response->json('state');

        return OrderExternalStatusEnum::from($state);
    }
}
