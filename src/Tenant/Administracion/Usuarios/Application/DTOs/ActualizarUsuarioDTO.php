<?php

declare(strict_types=1);

namespace Src\Tenant\Administracion\Usuarios\Application\DTOs;

final class ActualizarUsuarioDTO
{
    public function __construct(
        public readonly ?string $nombre = null,
        public readonly ?string $apellido = null,
        public readonly ?string $nombreUsuario = null,
        public readonly ?string $correoElectronico = null,
        public readonly ?string $contrasena = null
    ) {}

    public static function desdeArray(array $datos): self
    {
        return new self(
            nombre: $datos['nombre'] ?? null,
            apellido: $datos['apellido'] ?? null,
            nombreUsuario: $datos['nombre_usuario'] ?? null,
            correoElectronico: $datos['correo_electronico'] ?? null,
            contrasena: $datos['contrasena'] ?? null
        );
    }

    public function aArray(): array
    {
        $resultado = [];

        if ($this->nombre !== null) {
            $resultado['nombre'] = $this->nombre;
        }

        if ($this->apellido !== null) {
            $resultado['apellido'] = $this->apellido;
        }

        if ($this->nombreUsuario !== null) {
            $resultado['nombre_usuario'] = $this->nombreUsuario;
        }

        if ($this->correoElectronico !== null) {
            $resultado['correo_electronico'] = $this->correoElectronico;
        }

        if ($this->contrasena !== null) {
            $resultado['contrasena'] = $this->contrasena;
        }

        return $resultado;
    }

    public function tieneCambios(): bool
    {
        return $this->nombre !== null ||
               $this->apellido !== null ||
               $this->nombreUsuario !== null ||
               $this->correoElectronico !== null ||
               $this->contrasena !== null;
    }
}
