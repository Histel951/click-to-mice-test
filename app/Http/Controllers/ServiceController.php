<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Services\CatalogOfServices\Contracts\ServiceCatalogInterface;

class ServiceController extends Controller
{
    public function index(ServiceCatalogInterface $serviceCatalog): ApiResponse
    {
        $resultDto = $serviceCatalog->getServices();

        return new ApiResponse(
            data: $resultDto,
            message: 'Список услуг успешно получен'
        );
    }
}
