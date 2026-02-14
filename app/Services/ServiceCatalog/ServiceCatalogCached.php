<?php
declare(strict_types = 1);

namespace App\Services\ServiceCatalog;

use App\Services\ServiceCatalog\Contracts\ServiceCatalogInterface;
use App\Services\ServiceCatalog\DTO\Results\GetServicesByUuidsResultDto;
use App\Services\ServiceCatalog\DTO\Results\GetServicesResultDto;
use Illuminate\Support\Facades\Cache;

final readonly class ServiceCatalogCached implements ServiceCatalogInterface
{
    public function __construct(
        private ServiceCatalogInterface $serviceCatalog
    ) {}

    public function getServices(int $page): GetServicesResultDto
    {
        $cacheKey = "service_catalog:page:$page";

        return Cache::remember(
            $cacheKey,
            now()->addMinutes(10),
            fn () => $this->serviceCatalog->getServices($page)
        );
    }

    public function getServicesByUuids(array $uuids, int $page): GetServicesByUuidsResultDto
    {
        if ($uuids === []) {
            return new GetServicesByUuidsResultDto([]);
        }

        $result = [];
        $missingUuids = [];

        foreach ($uuids as $uuid) {
            $cached = Cache::get("service_catalog:uuid:$uuid");

            if ($cached !== null) {
                $result[] = $cached;
            } else {
                $missingUuids[] = $uuid;
            }
        }

        if ($missingUuids !== []) {
            $services = $this->getServices($page);

            foreach ($services->getServices() as $service) {
                $uuid = $service->getUuid()->toString();

                Cache::put(
                    "service_catalog:uuid:$uuid",
                    $service,
                    now()->addMinutes(30)
                );

                if (in_array($uuid, $missingUuids, true)) {
                    $result[] = $service;
                }
            }
        }

        return new GetServicesByUuidsResultDto($result);
    }
}
