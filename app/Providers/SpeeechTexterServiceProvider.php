<?php

namespace App\Providers;

use App\Repositories\Interfaces\SpeeechTexterRepositoryInterface;
use App\SpeeechTexter\Repositories\SpeeechTexterRepository;
use Illuminate\Support\ServiceProvider;

class SpeeechTexterServiceProvider extends SpeeechTexterServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SpeeechTexterRepositoryInterface::class, SpeeechTexterRepository::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
