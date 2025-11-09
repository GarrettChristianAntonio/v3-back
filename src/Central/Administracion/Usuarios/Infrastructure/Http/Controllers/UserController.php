<?php

declare(strict_types=1);

namespace Src\Central\Administracion\Usuarios\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Central\Administracion\Usuarios\Application\Services\UserService;
use Src\Central\Administracion\Usuarios\Application\DTOs\CrearUsuarioDTO;
use Src\Central\Administracion\Usuarios\Application\DTOs\ActualizarUsuarioDTO;
use Exception;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function index(): JsonResponse
    {
        try {
            $usuarios = $this->userService->obtenerTodosLosUsuarios();

            return response()->json([
                'success' => true,
                'data' => $usuarios,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuarios',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombre_usuario' => 'required|string|max:255|unique:usuarios,nombre_usuario',
                'contrasena' => 'required|string|min:6',
            ]);

            $dto = CrearUsuarioDTO::desdeArray($validated);
            $usuario = $this->userService->crearUsuario($dto);

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado exitosamente',
                'data' => $usuario->aArray(),
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear usuario',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $usuario = $this->userService->obtenerUsuarioPorId($id);

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $usuario->aArray(),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuario',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombre_usuario' => 'sometimes|string|max:255',
                'contrasena' => 'sometimes|string|min:6',
            ]);

            $dto = ActualizarUsuarioDTO::desdeArray($validated);
            $usuario = $this->userService->actualizarUsuario($id, $dto);

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado exitosamente',
                'data' => $usuario->aArray(),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar usuario',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $this->userService->eliminarUsuario($id);

            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado exitosamente',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar usuario',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
