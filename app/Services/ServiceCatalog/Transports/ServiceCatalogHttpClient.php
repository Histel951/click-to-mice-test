<?php
declare(strict_types = 1);

namespace App\Services\ServiceCatalog\Transports;

use App\Services\ServiceCatalog\DTO\Responses\GetHttpRawServicesResponseDto;
use App\Services\ServiceCatalog\Exceptions\ServiceCatalogConnectionException;
use App\Services\ServiceCatalog\Exceptions\ServiceCatalogInvalidDataException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

final readonly class ServiceCatalogHttpClient
{
    private const API_SERVICES = '/api/service';

    public function __construct(
        private string $url,
        private string $token,
    ) {}

    /**
     * Получает сырые данные с внешнего API сервиса
     *
     * @param int $page
     * @return GetHttpRawServicesResponseDto
     */
    public function getPage(int $page = 1) : GetHttpRawServicesResponseDto
    {
        try {
            $response = Http::withToken($this->token)
                ->get($this->url . self::API_SERVICES, ['page' => $page]);
        } catch (ConnectionException $e) {
            throw new ServiceCatalogConnectionException('Could not connect to external service', 0, $e);
        }

        $data = $response->json('data');
        if (!is_array($data)) {
            throw new ServiceCatalogInvalidDataException('Invalid response from external service: expected array');
        }

        $pagination = $response->json('pagination');

        return new GetHttpRawServicesResponseDto(
            $data,
            $pagination['page'],
            $pagination['pageSize'],
            $pagination['totalCount']
        );
    }
}
