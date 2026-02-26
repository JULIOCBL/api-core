<?php

namespace Src\Auth\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Provider para cargar traducciones del módulo Auth.
 */
class AuthLangServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Lang', 'auth');
    }
}
