<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Src\Tenant\Administracion\Usuarios\Infrastructure\Http\Controllers\UserController as TenantUserController;
use Src\Tenant\Administracion\Auth\Infrastructure\Http\Controllers\AuthController as TenantAuthController;

/*
|--------------------------------------------------------------------------
| Rutas API - Tenant
|--------------------------------------------------------------------------
|
| Rutas API para los tenants. Estas rutas se cargan automáticamente
| con el middleware de tenancy y tienen el prefijo /api.
|
*/

// Endpoint de prueba para verificar conexión del tenant
Route::get('/test-connection', function () {
    return response()->json([
        'success' => true,
        'message' => 'Conexión al tenant establecida exitosamente',
        'tenant' => [
            'id' => tenant('id'),
            'nombre' => tenant('nombre'),
            'subdominio' => tenant('subdominio'),
        ],
        'database' => [
            'connection' => 'tenant',
            'database_name' => tenant()->database()->getName(),
        ],
    ]);
});

// Rutas de autenticación (sin protección)
Route::prefix('auth')->group(function () {
    Route::post('/login', [TenantAuthController::class, 'login']);
});

// Rutas protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::get('/auth/me', [TenantAuthController::class, 'me']);
    Route::post('/auth/logout', [TenantAuthController::class, 'logout']);

    // Gestión de usuarios
    Route::prefix('users')->group(function () {
        Route::get('/', [TenantUserController::class, 'index']);
        Route::post('/', [TenantUserController::class, 'store']);
        Route::get('/{id}', [TenantUserController::class, 'show']);
        Route::put('/{id}', [TenantUserController::class, 'update']);
        Route::delete('/{id}', [TenantUserController::class, 'destroy']);
    });
});
