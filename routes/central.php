<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Src\Central\Administracion\GestionTenant\Infrastructure\Http\Controllers\TenantController;
use Src\Central\Administracion\Usuarios\Infrastructure\Http\Controllers\UserController as CentralUserController;
use Src\Central\Administracion\Auth\Infrastructure\Http\Controllers\AuthController as CentralAuthController;

/*
|--------------------------------------------------------------------------
| API Routes - Central
|--------------------------------------------------------------------------
*/

// Auth routes (Central) - Sin autenticación
Route::prefix('auth')->group(function () {
    Route::post('/login', [CentralAuthController::class, 'login']);
});

// Rutas protegidas con Sanctum (Central)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::get('/auth/me', [CentralAuthController::class, 'me']);
    Route::post('/auth/logout', [CentralAuthController::class, 'logout']);

    // Tenant management routes
    Route::prefix('tenants')->group(function () {
        Route::get('/', [TenantController::class, 'index']);
        Route::post('/', [TenantController::class, 'store']);
        Route::get('/{id}', [TenantController::class, 'show']);
        Route::put('/{id}', [TenantController::class, 'update']);
        Route::delete('/{id}', [TenantController::class, 'destroy']);

        // Crear usuarios en un tenant específico
        Route::post('/{tenantId}/usuarios', [TenantController::class, 'crearUsuario']);
    });

    // User management routes (Central)
    Route::prefix('users')->group(function () {
        Route::get('/', [CentralUserController::class, 'index']);
        Route::post('/', [CentralUserController::class, 'store']);
        Route::get('/{id}', [CentralUserController::class, 'show']);
        Route::put('/{id}', [CentralUserController::class, 'update']);
        Route::delete('/{id}', [CentralUserController::class, 'destroy']);
    });
});
