<?php

namespace App\Providers;

use App\Services\OrderService\Contracts\OrderServiceInterface;
use App\Services\OrderService\OrderService;
use App\Services\ServiceCatalog\Contracts\ServiceCatalogInterface;
use App\Services\ServiceCatalog\ServiceCatalogCached;
use App\Services\ServiceCatalog\ServiceCatalogHttp;
use App\Services\ServiceCatalog\Transports\ServiceCatalogHttpClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ServiceCatalogInterface::class, function () {
            $service = new ServiceCatalogHttp(
                new ServiceCatalogHttpClient(
                    url: config('service_catalog.base_url'),
                    token: config('service_catalog.api_token')
                )
            );

            return $service;

            return new ServiceCatalogCached($service);
        });

        $this->app->bind(OrderServiceInterface::class, OrderService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
