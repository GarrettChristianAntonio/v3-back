<?php

declare(strict_types=1);

namespace Src\Central\Administracion\Auth\Application\Services;

use Illuminate\Support\Facades\Hash;
use Src\Central\Administracion\Usuarios\Domain\Repositories\UserRepositoryInterface;
use Src\Central\Administracion\Usuarios\Application\DTOs\RespuestaUsuarioDTO;
use Src\Central\Shared\Application\DTOs\LoginRequestDTO;
use Src\Central\Shared\Application\DTOs\AuthTokenResponseDTO;
use Src\Central\Shared\Domain\ValueObjects\NombreUsuario;
use Exception;

class AuthService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function login(LoginRequestDTO $solicitudLogin): AuthTokenResponseDTO
    {
        $nombreUsuario = new NombreUsuario($solicitudLogin->nombreUsuario);

        $usuario = $this->userRepository->findByNameuser($nombreUsuario->valor());

        if (!$usuario || !Hash::check($solicitudLogin->contrasena, $usuario->contrasena)) {
            throw new Exception('Credenciales inválidas');
        }

        // Revocar tokens anteriores
        $usuario->tokens()->delete();

        // Crear nuevo token
        $token = $usuario->createToken('auth_token')->plainTextToken;

        $respuestaUsuario = RespuestaUsuarioDTO::desdeModelo($usuario);

        return AuthTokenResponseDTO::crear(
            usuario: $respuestaUsuario->aArray(),
            token: $token
        );
    }

    public function cerrarSesion($usuario): void
    {
        $usuario->tokens()->delete();
    }

    public function obtenerUsuarioActual($usuario): RespuestaUsuarioDTO
    {
        return RespuestaUsuarioDTO::desdeModelo($usuario);
    }
}
