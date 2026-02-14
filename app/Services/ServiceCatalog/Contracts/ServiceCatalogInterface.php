<?php

namespace App\Services\ServiceCatalog\Contracts;

use App\Services\ServiceCatalog\DTO\Results\GetServicesByUuidsResultDto;
use App\Services\ServiceCatalog\DTO\Results\GetServicesResultDto;
use App\Services\ServiceCatalog\Exceptions\ServiceCatalogConnectionException;
use App\Services\ServiceCatalog\Exceptions\ServiceCatalogInvalidDataException;

interface ServiceCatalogInterface
{
    /**
     * Получение списка услуг
     *
     * @param int $page
     * @return GetServicesResultDto
     * @throws ServiceCatalogInvalidDataException
     * @throws ServiceCatalogConnectionException
     */
    public function getServices(int $page): GetServicesResultDto;

    /**
     * Получение услуг по определённым uuid
     *
     * @param array $uuids
     * @param int $page
     * @return GetServicesByUuidsResultDto
     */
    public function getServicesByUuids(array $uuids, int $page): GetServicesByUuidsResultDto;
}
