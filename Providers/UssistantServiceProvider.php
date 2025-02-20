<?php

namespace App\Services\Ussistant\Providers;

use App\Services\Ussistant\Repositories\Interfaces\UssistantRepositoryInterface;
use App\Services\Ussistant\Repositories\UssistantRepository;
use Illuminate\Support\ServiceProvider;

class UssistantServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UssistantRepositoryInterface::class, UssistantRepository::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
