<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Rutas API de Central - restringidas a dominios centrales
            foreach (config('tenancy.central_domains') as $domain) {
                Route::domain($domain)
                    ->prefix('api')
                    ->middleware('api')
                    ->group(base_path('routes/central.php'));
            }
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('tenant', [
            \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
            \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
