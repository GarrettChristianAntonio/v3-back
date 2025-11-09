<?php

declare(strict_types=1);

namespace Src\Central\Administracion\Usuarios\Application\DTOs;

final class ActualizarUsuarioDTO
{
    public function __construct(
        public readonly ?string $nombreUsuario = null,
        public readonly ?string $contrasena = null
    ) {}

    public static function desdeArray(array $datos): self
    {
        return new self(
            nombreUsuario: $datos['nombre_usuario'] ?? null,
            contrasena: $datos['contrasena'] ?? null
        );
    }

    public function aArray(): array
    {
        $resultado = [];

        if ($this->nombreUsuario !== null) {
            $resultado['nombre_usuario'] = $this->nombreUsuario;
        }

        if ($this->contrasena !== null) {
            $resultado['contrasena'] = $this->contrasena;
        }

        return $resultado;
    }

    public function tieneCambios(): bool
    {
        return $this->nombreUsuario !== null || $this->contrasena !== null;
    }
}
