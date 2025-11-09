<?php

/**
 * Script de prueba de la API de Tenants
 * Ejecutar: php test_api.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

echo "\n=== Prueba de API REST de Tenants ===\n\n";

// Test 1: Crear un tenant
echo "1. POST /api/tenants - Crear nuevo tenant\n";
$request = \Illuminate\Http\Request::create(
    '/api/tenants',
    'POST',
    [
        'nombre' => 'Clinica San Martin',
        'subdominio' => 'clinicasanmartin',
        'telefono' => '+54 11 5555-1234',
    ]
);

$response = $kernel->handle($request);
echo "   Status: " . $response->getStatusCode() . "\n";
echo "   Response: " . $response->getContent() . "\n\n";

$responseData = json_decode($response->getContent(), true);
$tenantId = $responseData['data']['id'] ?? null;

// Test 2: Listar todos los tenants
echo "2. GET /api/tenants - Listar todos los tenants\n";
$request = \Illuminate\Http\Request::create('/api/tenants', 'GET');
$response = $kernel->handle($request);
echo "   Status: " . $response->getStatusCode() . "\n";
echo "   Response: " . $response->getContent() . "\n\n";

// Test 3: Obtener un tenant específico
if ($tenantId) {
    echo "3. GET /api/tenants/{id} - Obtener tenant específico\n";
    $request = \Illuminate\Http\Request::create("/api/tenants/{$tenantId}", 'GET');
    $response = $kernel->handle($request);
    echo "   Status: " . $response->getStatusCode() . "\n";
    echo "   Response: " . $response->getContent() . "\n\n";
}

echo "=== Pruebas completadas ===\n\n";

$kernel->terminate($request, $response);
