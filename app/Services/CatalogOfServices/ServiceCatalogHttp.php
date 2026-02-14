<?php
declare(strict_types = 1);

namespace App\Services\CatalogOfServices;

use App\Services\CatalogOfServices\Contracts\ServiceCatalogInterface;
use App\Services\CatalogOfServices\DTO\Data\ServiceDto;
use App\Services\CatalogOfServices\Exceptions\ServiceCatalogConnectionException;
use App\Services\CatalogOfServices\Exceptions\ServiceCatalogInvalidDataException;
use App\Services\CatalogOfServices\Transports\ServiceCatalogHttpClient;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

final readonly class ServiceCatalogHttp implements ServiceCatalogInterface
{
    public function __construct(private ServiceCatalogHttpClient $client) {}

    /**
     * @return ServiceDto[]
     * @throws ServiceCatalogInvalidDataException
     * @throws ServiceCatalogConnectionException
     */
    public function getServices(): array
    {
        $responseDto = $this->client->getPage();

        return array_map(fn($service) => new ServiceDto(
            uuid: Uuid::fromString($service['uuid']),
            name: $service['name'],
            description: $service['description'],
            price: $service['price'],
            tax: $service['tax'],
            gross: $service['gross'],
            image: $service['image'],
            updatedAt: Carbon::make($service['updated_at']),
        ), $responseDto->getData());
    }

    /**
     * @param array $uuids
     * @return ServiceDto[]
     */
    public function getServicesByUuids(array $uuids): array
    {
        if (empty($uuids)) {
            return [];
        }

        $services = $this->getServices();

        return array_values(array_filter(
            $services,
            fn (ServiceDto $service) =>
            in_array($service->getUuid()->toString(), $uuids, true)
        ));
    }
}
