<?php


namespace Src\Core\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Core\Infrastructure\Console\Commands\GenerateKeyPair;

class CommandServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
         if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateKeyPair::class,
            ]);
        }
    }
}
