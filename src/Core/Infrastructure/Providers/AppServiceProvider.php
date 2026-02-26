<?php


namespace Src\Core\Infrastructure\Providers;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Src\Auth\Domain\Contracts\AuthGatewayInterface;
use Src\Auth\Infrastructure\Persistence\EloquentAuthGateway;
use Src\Companies\Domain\Contracts\CompanyRepositoryInterface;
use Src\Companies\Infrastructure\Persistence\EloquentCompanyRepository;
use Src\Permissions\Application\Contracts\GetAssignedModulesByPlatformInterface;
use Src\Permissions\Application\UseCases\GetAssignedModulesByPlatform;
use Src\Permissions\Domain\Contracts\PermissionInterface;
use Src\Permissions\Infrastructure\Persistence\EloquentPermissionRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(AuthGatewayInterface::class, EloquentAuthGateway::class);
        $this->app->bind(CompanyRepositoryInterface::class, EloquentCompanyRepository::class);
        $this->app->bind(GetAssignedModulesByPlatformInterface::class, GetAssignedModulesByPlatform::class);
        $this->app->bind(PermissionInterface::class, EloquentPermissionRepository::class);
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
    protected function isMigratingOrSeeding(): bool
    {
        $commands = ['migrate',/*  'db:seed', */ 'migrate:rollback', 'migrate:refresh'];

        // Verifica si $_SERVER['argv'] es un array antes de usarlo
        $current_command = $this->app->runningInConsole() && isset($_SERVER['argv']) && is_array($_SERVER['argv'])
            ? trim(implode(' ', $_SERVER['argv']))
            : '';

        foreach ($commands as $command) {
            if (strpos($current_command, $command) !== false) {
                return true;
            }
        }

        return false;
    }
}
