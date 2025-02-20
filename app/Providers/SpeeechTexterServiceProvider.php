<?php

namespace SpeeechTexter\Providers;

use SpeeechTexter\Repositories\Interfaces\SpeeechTexterRepositoryInterface;
use SpeeechTexter\Repositories\SpeeechTexterRepository;
use Illuminate\Support\ServiceProvider;

class SpeeechTexterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SpeeechTexterRepositoryInterface::class, SpeeechTexterRepository::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
    }
}
