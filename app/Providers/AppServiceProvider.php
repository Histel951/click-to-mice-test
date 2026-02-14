<?php

namespace App\Providers;

use App\Exceptions\Handler;
use App\Services\OrderService\Contracts\OrderServiceInterface;
use App\Services\OrderService\OrderService;
use App\Services\OrderService\Transports\OrderHttpClient;
use App\Services\CatalogOfServices\Contracts\ServiceCatalogInterface;
use App\Services\CatalogOfServices\ServiceCatalogCached;
use App\Services\CatalogOfServices\ServiceCatalogHttp;
use App\Services\CatalogOfServices\Transports\ServiceCatalogHttpClient;
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

            return new ServiceCatalogCached($service);
        });

        $this->app->singleton(OrderHttpClient::class, function () {
            return new OrderHttpClient(
                url: config('service_order.base_url'),
                token: config('service_order.api_token')
            );
        });

        $this->app->bind(OrderServiceInterface::class, OrderService::class);

        $this->app->singleton(\Illuminate\Foundation\Exceptions\Handler::class, Handler::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
