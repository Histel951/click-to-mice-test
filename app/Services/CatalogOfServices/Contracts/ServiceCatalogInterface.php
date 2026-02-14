<?php

namespace App\Services\CatalogOfServices\Contracts;

use App\Services\CatalogOfServices\DTO\Data\ServiceDto;
use App\Services\CatalogOfServices\Exceptions\ServiceCatalogConnectionException;
use App\Services\CatalogOfServices\Exceptions\ServiceCatalogInvalidDataException;

interface ServiceCatalogInterface
{
    /**
     * Получение списка услуг
     *
     * @return ServiceDto[]
     * @throws ServiceCatalogInvalidDataException
     * @throws ServiceCatalogConnectionException
     */
    public function getServices(): array;

    /**
     * Получение услуг по определённым uuid
     *
     * @param array $uuids
     * @param int $page
     * @return ServiceDto[]
     */
    public function getServicesByUuids(array $uuids): array;
}
