<?php

namespace Src\Core\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Core\Infrastructure\Console\Commands\GenerateKeyPair;

class CommandServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
         if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateKeyPair::class,
            ]);
        }
    }
}
