<?php

namespace Shop\Reference\Infrastructure\Provider;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Shop\Reference\Domain\Repository\ReferenceRepository;
use Shop\Reference\Infrastructure\Repository\EloquentReferenceRepository;

class ReferenceServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerRoute();
        $this->bindModuleRepositories();
    }

    private function bindModuleRepositories(): void
    {
        $this->app->singleton(ReferenceRepository::class, EloquentReferenceRepository::class);
    }

    public function registerRoute():void
    {
        Route::group($this->routeConfig(), function () {
            $this->loadRoutesFrom(
                base_path('/src/Reference/Infrastructure/routes/web.php')
            );
        });
    }

    private function routeConfig():array
    {
        return [
            'prefix' => 'app',
            'middleware' => ['web']
        ];
    }
}
