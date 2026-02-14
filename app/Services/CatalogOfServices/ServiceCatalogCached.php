<?php
declare(strict_types = 1);

namespace App\Services\CatalogOfServices;

use App\Services\CatalogOfServices\Contracts\ServiceCatalogInterface;
use Illuminate\Support\Facades\Cache;

final readonly class ServiceCatalogCached implements ServiceCatalogInterface
{
    public function __construct(
        private ServiceCatalogInterface $serviceCatalog
    ) {}

    public function getServices(): array
    {
        $cacheKey = "service_catalog:page";

        return Cache::remember(
            $cacheKey,
            now()->addMinutes(10),
            fn () => $this->serviceCatalog->getServices()
        );
    }

    public function getServicesByUuids(array $uuids): array
    {
        if ($uuids === []) {
            return [];
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
            $services = $this->getServices();

            foreach ($services as $service) {
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

        return $result;
    }
}
