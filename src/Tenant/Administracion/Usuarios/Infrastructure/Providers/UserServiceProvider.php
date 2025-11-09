<?php

declare(strict_types=1);

namespace Src\Tenant\Administracion\Usuarios\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Tenant\Administracion\Usuarios\Domain\Repositories\UserRepositoryInterface;
use Src\Tenant\Administracion\Usuarios\Infrastructure\Persistence\EloquentUserRepository;

class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Use when() for context-aware binding
        $this->app
            ->when(\Src\Tenant\Administracion\Auth\Application\Services\AuthService::class)
            ->needs(\Src\Tenant\Administracion\Usuarios\Domain\Repositories\UserRepositoryInterface::class)
            ->give(\Src\Tenant\Administracion\Usuarios\Infrastructure\Persistence\EloquentUserRepository::class);

        $this->app
            ->when(\Src\Tenant\Administracion\Usuarios\Application\Services\UserService::class)
            ->needs(\Src\Tenant\Administracion\Usuarios\Domain\Repositories\UserRepositoryInterface::class)
            ->give(\Src\Tenant\Administracion\Usuarios\Infrastructure\Persistence\EloquentUserRepository::class);

        $this->app
            ->when(\Src\Tenant\Administracion\Usuarios\Infrastructure\Http\Controllers\UserController::class)
            ->needs(\Src\Tenant\Administracion\Usuarios\Application\Services\UserService::class)
            ->give(function ($app) {
                return new \Src\Tenant\Administracion\Usuarios\Application\Services\UserService(
                    $app->make(\Src\Tenant\Administracion\Usuarios\Infrastructure\Persistence\EloquentUserRepository::class)
                );
            });
    }

    public function boot(): void
    {
        //
    }
}
