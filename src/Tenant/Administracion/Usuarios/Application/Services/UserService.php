<?php

declare(strict_types=1);

namespace Src\Tenant\Administracion\Usuarios\Application\Services;

use Illuminate\Support\Facades\Hash;
use Src\Tenant\Administracion\Usuarios\Domain\Repositories\UserRepositoryInterface;
use Src\Tenant\Administracion\Usuarios\Application\DTOs\CrearUsuarioDTO;
use Src\Tenant\Administracion\Usuarios\Application\DTOs\ActualizarUsuarioDTO;
use Src\Tenant\Administracion\Usuarios\Application\DTOs\RespuestaUsuarioDTO;
use Src\Tenant\Shared\Domain\ValueObjects\NombreUsuario;
use Src\Tenant\Shared\Domain\ValueObjects\CorreoElectronico;
use Src\Tenant\Shared\Domain\ValueObjects\Uuid;
use Exception;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function crearUsuario(CrearUsuarioDTO $dto): RespuestaUsuarioDTO
    {
        $nombreUsuario = new NombreUsuario($dto->nombreUsuario);
        $correoElectronico = new CorreoElectronico($dto->correoElectronico);

        if ($this->userRepository->findByNameuser($nombreUsuario->valor())) {
            throw new Exception('El nombre de usuario ya existe');
        }

        $datos = [
            'nombre' => $dto->nombre,
            'apellido' => $dto->apellido,
            'nombre_usuario' => $nombreUsuario->valor(),
            'correo_electronico' => $correoElectronico->valor(),
            'contrasena' => Hash::make($dto->contrasena),
        ];

        $usuario = $this->userRepository->create($datos);

        return RespuestaUsuarioDTO::desdeModelo($usuario);
    }

    public function obtenerUsuarioPorNombreUsuario(string $nombreUsuario): ?RespuestaUsuarioDTO
    {
        $nombreUsuarioVO = new NombreUsuario($nombreUsuario);
        $usuario = $this->userRepository->findByNameuser($nombreUsuarioVO->valor());

        return $usuario ? RespuestaUsuarioDTO::desdeModelo($usuario) : null;
    }

    public function obtenerUsuarioPorId(string $id): ?RespuestaUsuarioDTO
    {
        $uuid = new Uuid($id);
        $usuario = $this->userRepository->findById($uuid->valor());

        return $usuario ? RespuestaUsuarioDTO::desdeModelo($usuario) : null;
    }

    public function actualizarUsuario(string $id, ActualizarUsuarioDTO $dto): RespuestaUsuarioDTO
    {
        $uuid = new Uuid($id);

        if ($dto->nombreUsuario !== null) {
            $nombreUsuario = new NombreUsuario($dto->nombreUsuario);
            $existente = $this->userRepository->findByNameuser($nombreUsuario->valor());
            if ($existente && $existente->id !== $uuid->valor()) {
                throw new Exception('El nombre de usuario ya existe');
            }
        }

        $datos = [];
        if ($dto->nombre !== null) {
            $datos['nombre'] = $dto->nombre;
        }
        if ($dto->apellido !== null) {
            $datos['apellido'] = $dto->apellido;
        }
        if ($dto->nombreUsuario !== null) {
            $datos['nombre_usuario'] = (new NombreUsuario($dto->nombreUsuario))->valor();
        }
        if ($dto->correoElectronico !== null) {
            $datos['correo_electronico'] = (new CorreoElectronico($dto->correoElectronico))->valor();
        }
        if ($dto->contrasena !== null) {
            $datos['contrasena'] = Hash::make($dto->contrasena);
        }

        $usuario = $this->userRepository->update($uuid->valor(), $datos);

        return RespuestaUsuarioDTO::desdeModelo($usuario);
    }

    public function eliminarUsuario(string $id): bool
    {
        $uuid = new Uuid($id);
        return $this->userRepository->delete($uuid->valor());
    }

    public function obtenerTodosLosUsuarios(): array
    {
        $usuarios = $this->userRepository->all();

        return array_map(
            fn($usuario) => RespuestaUsuarioDTO::desdeModelo($usuario),
            $usuarios->all()
        );
    }
}
