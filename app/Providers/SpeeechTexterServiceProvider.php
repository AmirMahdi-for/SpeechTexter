<?php

namespace SpeechTexter\Providers;

use SpeechTexter\Repositories\Interfaces\SpeechTexterRepositoryInterface;
use SpeechTexter\Repositories\SpeechTexterRepository;
use Illuminate\Support\ServiceProvider;

class SpeechTexterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/speech-texter.php', 'speech-texter');
        
        $this->app->bind(SpeechTexterRepositoryInterface::class, SpeechTexterRepository::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/speech-texter.php' => config_path('speech-texter.php'),
        ], 'config');
        
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
    }
}
