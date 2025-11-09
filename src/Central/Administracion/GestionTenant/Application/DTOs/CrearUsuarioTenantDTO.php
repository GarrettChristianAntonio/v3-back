<?php

declare(strict_types=1);

namespace Src\Central\Administracion\GestionTenant\Application\DTOs;

final class CrearUsuarioTenantDTO
{
    public function __construct(
        public readonly string $tenantId,
        public readonly string $nombre,
        public readonly string $apellido,
        public readonly string $nombreUsuario,
        public readonly string $correoElectronico,
        public readonly string $contrasena
    ) {}

    public static function desdeArray(string $tenantId, array $datos): self
    {
        return new self(
            tenantId: $tenantId,
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
            'tenant_id' => $this->tenantId,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'nombre_usuario' => $this->nombreUsuario,
            'correo_electronico' => $this->correoElectronico,
        ];
    }
}
