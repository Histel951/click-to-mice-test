<?php

namespace App\Http\Controllers;

use App\Http\Requests\Service\ServiceListRequest;
use App\Http\Responses\ApiResponse;
use App\Services\ServiceCatalog\Contracts\ServiceCatalogInterface;

class ServiceController extends Controller
{
    public function index(ServiceListRequest $request, ServiceCatalogInterface $serviceCatalog): ApiResponse
    {
        $page = $request->getPage();
        $resultDto = $serviceCatalog->getServices($page);

        return new ApiResponse(
            data: $resultDto->getServices(),
            message: 'Список услуг успешно получен'
        );
    }
}
