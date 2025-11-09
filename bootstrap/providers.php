<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\TenancyServiceProvider::class,
    Src\Central\Administracion\GestionTenant\Infrastructure\Providers\TenantServiceProvider::class,
    Src\Central\Administracion\Usuarios\Infrastructure\Providers\UserServiceProvider::class,
    Src\Tenant\Administracion\Usuarios\Infrastructure\Providers\UserServiceProvider::class,
];
