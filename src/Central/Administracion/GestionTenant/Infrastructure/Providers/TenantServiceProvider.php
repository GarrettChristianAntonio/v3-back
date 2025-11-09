<?php

declare(strict_types=1);

namespace Src\Central\Administracion\GestionTenant\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Central\Administracion\GestionTenant\Domain\Repositories\TenantRepositoryInterface;
use Src\Central\Administracion\GestionTenant\Infrastructure\Persistence\EloquentTenantRepository;

class TenantServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            TenantRepositoryInterface::class,
            EloquentTenantRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
