<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\ICategoryRepository;
use App\Repositories\IProductConfigurationRepo;
use App\Repositories\IProductItemRepository;
use App\Repositories\IProductRepository;
use App\Repositories\IVariationOptionRepository;
use App\Repositories\IVariationRepository;
use App\Repositories\ProductConfigurationRepo;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
