<?php

namespace Src\Shared\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Provider para cargar traducciones del módulo Shared.
 */
class SharedLangServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Lang', 'shared');
    }
}
