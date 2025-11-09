<?php

declare(strict_types=1);

namespace Src\Central\Administracion\GestionTenant\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Central\Administracion\GestionTenant\Application\Services\TenantService;
use Src\Central\Administracion\GestionTenant\Application\Services\TenantUserService;
use Src\Central\Administracion\GestionTenant\Application\DTOs\CrearTenantDTO;
use Src\Central\Administracion\GestionTenant\Application\DTOs\ActualizarTenantDTO;
use Src\Central\Administracion\GestionTenant\Application\DTOs\CrearUsuarioTenantDTO;
use Exception;

class TenantController extends Controller
{
    public function __construct(
        private TenantService $tenantService,
        private TenantUserService $tenantUserService
    ) {}

    /**
     * Listar todos los tenants
     */
    public function index(): JsonResponse
    {
        try {
            $tenants = $this->tenantService->obtenerTodosLosTenants();

            return response()->json([
                'success' => true,
                'data' => $tenants,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los tenants',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Crear un nuevo tenant
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'subdominio' => 'required|string|max:255|unique:tenants,subdominio|regex:/^[a-z0-9-]+$/',
            ]);

            $dto = CrearTenantDTO::desdeArray($validated);
            $tenant = $this->tenantService->crearTenant($dto);

            return response()->json([
                'success' => true,
                'message' => 'Tenant creado exitosamente',
                'data' => $tenant->aArray(),
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el tenant',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Mostrar un tenant específico
     */
    public function show(string $id): JsonResponse
    {
        try {
            $tenant = $this->tenantService->obtenerTenantPorId($id);

            if (!$tenant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tenant no encontrado',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $tenant->aArray(),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el tenant',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Actualizar un tenant
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombre' => 'sometimes|string|max:255',
                'subdominio' => 'sometimes|string|max:255|regex:/^[a-z0-9-]+$/|unique:tenants,subdominio,' . $id,
            ]);

            $dto = ActualizarTenantDTO::desdeArray($validated);
            $tenant = $this->tenantService->actualizarTenant($id, $dto);

            return response()->json([
                'success' => true,
                'message' => 'Tenant actualizado exitosamente',
                'data' => $tenant->aArray(),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el tenant',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Eliminar un tenant
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->tenantService->eliminarTenant($id);

            return response()->json([
                'success' => true,
                'message' => 'Tenant eliminado exitosamente',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el tenant',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Crear un usuario en un tenant específico
     */
    public function crearUsuario(Request $request, string $tenantId): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'nombre_usuario' => 'required|string|max:255',
                'correo_electronico' => 'required|email|max:255',
                'contrasena' => 'required|string|min:6',
            ]);

            $dto = CrearUsuarioTenantDTO::desdeArray($tenantId, $validated);
            $usuario = $this->tenantUserService->crearUsuarioEnTenant($dto);

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado exitosamente en el tenant',
                'data' => $usuario,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el usuario',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
