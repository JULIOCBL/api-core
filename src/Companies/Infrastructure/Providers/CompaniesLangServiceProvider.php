<?php

namespace Src\Companies\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Provider para cargar traducciones del módulo Companies.
 */
class CompaniesLangServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Lang', 'companies');
    }
}
