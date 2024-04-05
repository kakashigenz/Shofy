<?php

namespace App\Providers;

use App\Repositories\AddressRepository;
use App\Repositories\AttributeProductRepository;
use App\Repositories\AttributeRepository;
use App\Repositories\AttributeValueRepository;
use App\Repositories\CartItemRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\IAddressRepository;
use App\Repositories\IAttributeProductRepository;
use App\Repositories\IAttributeRepository;
use App\Repositories\IAttributeValueRepository;
use App\Repositories\ICartItemRepository;
use App\Repositories\ICategoryRepository;
use App\Repositories\IOrderDetailRepository;
use App\Repositories\IOrderRepository;
use App\Repositories\IProductConfigurationRepo;
use App\Repositories\IProductImageRepository;
use App\Repositories\IProductItemRepository;
use App\Repositories\IProductRepository;
use App\Repositories\IVariationOptionRepository;
use App\Repositories\IVariationRepository;
use App\Repositories\OrderDetailRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductConfigurationRepo;
use App\Repositories\ProductImageRepository;
use App\Repositories\ProductItemRepository;
use App\Repositories\ProductRepository;
use App\Repositories\VariationOptionRepository;
use App\Repositories\VariationRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ICategoryRepository::class, CategoryRepository::class);
        $this->app->bind(IVariationRepository::class, VariationRepository::class);
        $this->app->bind(IProductRepository::class, ProductRepository::class);
        $this->app->bind(IProductItemRepository::class, ProductItemRepository::class);
        $this->app->bind(IVariationOptionRepository::class, VariationOptionRepository::class);
        $this->app->bind(IProductConfigurationRepo::class, ProductConfigurationRepo::class);
        $this->app->bind(IAttributeRepository::class,AttributeRepository::class);
        $this->app->bind(IAttributeValueRepository::class,AttributeValueRepository::class);
        $this->app->bind(IProductImageRepository::class,ProductImageRepository::class);
        $this->app->bind(IAttributeProductRepository::class,AttributeProductRepository::class);
        $this->app->bind(IOrderRepository::class,OrderRepository::class);
        $this->app->bind(IOrderDetailRepository::class,OrderDetailRepository::class);
        $this->app->bind(ICartItemRepository::class,CartItemRepository::class);
        $this->app->bind(IAddressRepository::class,AddressRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
