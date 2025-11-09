<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Binding del resolver personalizado para usar 'dominio' en lugar de 'domain'
        $this->app->bind(
            \Stancl\Tenancy\Resolvers\DomainTenantResolver::class,
            \App\Resolvers\DomainTenantResolver::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar el modelo personalizado de PersonalAccessToken para Central
        Sanctum::usePersonalAccessTokenModel(
            \Src\Central\Administracion\Usuarios\Domain\Models\PersonalAccessToken::class
        );
    }
}
