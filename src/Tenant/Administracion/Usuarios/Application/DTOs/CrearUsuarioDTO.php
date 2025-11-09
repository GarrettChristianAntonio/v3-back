<?php

declare(strict_types=1);

namespace Src\Tenant\Administracion\Usuarios\Application\DTOs;

final class CrearUsuarioDTO
{
    public function __construct(
        public readonly string $nombre,
        public readonly string $apellido,
        public readonly string $nombreUsuario,
        public readonly string $correoElectronico,
        public readonly string $contrasena
    ) {}

    public static function desdeArray(array $datos): self
    {
        return new self(
            nombre: $datos['nombre'] ?? '',
            apellido: $datos['apellido'] ?? '',
            nombreUsuario: $datos['nombre_usuario'] ?? '',
            correoElectronico: $datos['correo_electronico'] ?? '',
            contrasena: $datos['contrasena'] ?? ''
        );
    }

    public function aArray(): array
    {
        return [
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'nombre_usuario' => $this->nombreUsuario,
            'correo_electronico' => $this->correoElectronico,
            'contrasena' => $this->contrasena,
        ];
    }
}
