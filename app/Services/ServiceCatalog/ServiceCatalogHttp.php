<?php
declare(strict_types = 1);

namespace App\Services\ServiceCatalog;

use App\Services\ServiceCatalog\Contracts\ServiceCatalogInterface;
use App\Services\ServiceCatalog\DTO\Objects\ServiceDto;
use App\Services\ServiceCatalog\DTO\Results\GetServicesByUuidsResultDto;
use App\Services\ServiceCatalog\DTO\Results\GetServicesResultDto;
use App\Services\ServiceCatalog\Exceptions\ServiceCatalogConnectionException;
use App\Services\ServiceCatalog\Exceptions\ServiceCatalogInvalidDataException;
use App\Services\ServiceCatalog\Transports\ServiceCatalogHttpClient;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

final readonly class ServiceCatalogHttp implements ServiceCatalogInterface
{
    public function __construct(private ServiceCatalogHttpClient $client) {}

    /**
     * @param int $page
     * @return GetServicesResultDto
     * @throws ServiceCatalogInvalidDataException
     * @throws ServiceCatalogConnectionException
     */
    public function getServices(int $page): GetServicesResultDto
    {
        $responseDto = $this->client->getPage($page);

        $services = array_map(fn($service) => new ServiceDto(
            uuid: Uuid::fromString($service['uuid']),
            name: $service['name'],
            description: $service['description'],
            price: $service['price'],
            tax: $service['tax'],
            gross: $service['gross'],
            image: $service['image'],
            updatedAt: Carbon::make($service['updated_at']),
        ), $responseDto->getData());

        return new GetServicesResultDto($services);
    }

    /**
     * @param array $uuids
     * @param int $page
     * @return GetServicesByUuidsResultDto
     */
    public function getServicesByUuids(array $uuids, int $page): GetServicesByUuidsResultDto
    {
        if (empty($uuids)) {
            return new GetServicesByUuidsResultDto([]);
        }

        $services = $this->getServices($page);

        $resultServices = array_values(array_filter(
            $services->getServices(),
            fn (ServiceDto $service) =>
            in_array($service->getUuid()->toString(), $uuids, true)
        ));

        return new GetServicesByUuidsResultDto($resultServices);
    }
}
