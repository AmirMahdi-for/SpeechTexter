<?php

namespace SpeeechTexter\Providers;

use SpeeechTexter\Repositories\Interfaces\SpeeechTexterRepositoryInterface;
use SpeeechTexter\Repositories\SpeeechTexterRepository;
use Illuminate\Support\ServiceProvider;

class SpeeechTexterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/speeech-texter.php', 'speeech-texter');
        
        $this->app->bind(SpeeechTexterRepositoryInterface::class, SpeeechTexterRepository::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/speeech-texter.php' => config_path('speeech-texter.php'),
        ], 'config');
        
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
    }
}
