<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Shop\Reference\Infrastructure\Provider\ReferenceServiceProvider;
use Shop\shared\Infrastructure\Database\EloquentPdoConnexion;
use Shop\shared\Library\PdoConnexion;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(ReferenceServiceProvider::class);
        $this->bindModuleRepositories();
    }

    /**
     * @return void
     */
    public function bindModuleRepositories(): void
    {
        $this->app->singleton(PdoConnexion::class, EloquentPdoConnexion::class);
    }
}
