<?php

declare(strict_types=1);

namespace Src\Tenant\Shared\Application\DTOs;

final class LoginRequestDTO
{
    public function __construct(
        public readonly string $nombreUsuario,
        public readonly string $contrasena
    ) {}

    public static function desdeArray(array $datos): self
    {
        return new self(
            nombreUsuario: $datos['nombre_usuario'] ?? '',
            contrasena: $datos['contrasena'] ?? ''
        );
    }

    public function aArray(): array
    {
        return [
            'nombre_usuario' => $this->nombreUsuario,
            'contrasena' => $this->contrasena,
        ];
    }
}
