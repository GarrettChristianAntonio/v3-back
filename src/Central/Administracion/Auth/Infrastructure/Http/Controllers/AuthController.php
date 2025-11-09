<?php

declare(strict_types=1);

namespace Src\Central\Administracion\Auth\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Central\Administracion\Auth\Application\Services\AuthService;
use Src\Central\Shared\Application\DTOs\LoginRequestDTO;
use Exception;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function login(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombre_usuario' => 'required|string',
                'contrasena' => 'required|string',
            ]);

            $loginRequest = LoginRequestDTO::desdeArray($validated);
            $result = $this->authService->login($loginRequest);

            return response()->json([
                'success' => true,
                'message' => 'Login exitoso',
                'data' => $result->aArray(),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el login',
                'error' => $e->getMessage(),
            ], 401);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $this->authService->cerrarSesion($request->user());

            return response()->json([
                'success' => true,
                'message' => 'Logout exitoso',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el logout',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function me(Request $request): JsonResponse
    {
        try {
            $result = $this->authService->obtenerUsuarioActual($request->user());

            return response()->json([
                'success' => true,
                'data' => $result->aArray(),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuario',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
