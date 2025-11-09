<?php

declare(strict_types=1);

namespace Src\Central\Administracion\Usuarios\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Central\Administracion\Usuarios\Domain\Repositories\UserRepositoryInterface;
use Src\Central\Administracion\Usuarios\Infrastructure\Persistence\EloquentUserRepository;

class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Use when() for context-aware binding
        $this->app
            ->when(\Src\Central\Administracion\Auth\Application\Services\AuthService::class)
            ->needs(\Src\Central\Administracion\Usuarios\Domain\Repositories\UserRepositoryInterface::class)
            ->give(\Src\Central\Administracion\Usuarios\Infrastructure\Persistence\EloquentUserRepository::class);

        $this->app
            ->when(\Src\Central\Administracion\Usuarios\Application\Services\UserService::class)
            ->needs(\Src\Central\Administracion\Usuarios\Domain\Repositories\UserRepositoryInterface::class)
            ->give(\Src\Central\Administracion\Usuarios\Infrastructure\Persistence\EloquentUserRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
