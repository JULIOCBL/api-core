<?php

namespace Src\Core\Infrastructure\Providers;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
     public function boot()
    {
        if ($this->app->runningInConsole() && $this->isMigratingOrSeeding()) {
            // Cambia las credenciales para la base de datos principal
            Config::set('database.connections.mariadb.username', env('DB_MIGRATION_USERNAME'));
            Config::set('database.connections.mariadb.password', env('DB_MIGRATION_PASSWORD'));

            // Cambia las credenciales para la base de datos de logs
            Config::set('database.connections.logs.username', env('DB_MIGRATION_USERNAME'));
            Config::set('database.connections.logs.password', env('DB_MIGRATION_PASSWORD'));
        }
    }

    /**
     * Verifica si se está ejecutando un comando de migración o seeding.
     *
     * @return bool
     */
    protected function isMigratingOrSeeding()
    {
        $commands = ['migrate',/*  'db:seed', */ 'migrate:rollback', 'migrate:refresh'];

        // Verifica si $_SERVER['argv'] es un array antes de usarlo
        $currentCommand = $this->app->runningInConsole() && isset($_SERVER['argv']) && is_array($_SERVER['argv'])
            ? trim(implode(' ', $_SERVER['argv']))
            : '';

        foreach ($commands as $command) {
            if (strpos($currentCommand, $command) !== false) {
                return true;
            }
        }

        return false;
    }
}
