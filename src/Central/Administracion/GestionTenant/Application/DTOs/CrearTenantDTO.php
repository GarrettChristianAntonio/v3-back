<?php

declare(strict_types=1);

namespace Src\Central\Administracion\GestionTenant\Application\DTOs;

final class CrearTenantDTO
{
    public function __construct(
        public readonly string $nombre,
        public readonly string $subdominio
    ) {}

    public static function desdeArray(array $datos): self
    {
        return new self(
            nombre: $datos['nombre'] ?? '',
            subdominio: $datos['subdominio'] ?? ''
        );
    }

    public function aArray(): array
    {
        return [
            'nombre' => $this->nombre,
            'subdominio' => $this->subdominio,
        ];
    }
}
