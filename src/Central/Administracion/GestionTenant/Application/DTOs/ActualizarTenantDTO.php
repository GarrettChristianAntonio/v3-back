<?php

declare(strict_types=1);

namespace Src\Central\Administracion\GestionTenant\Application\DTOs;

final class ActualizarTenantDTO
{
    public function __construct(
        public readonly ?string $nombre = null,
        public readonly ?string $subdominio = null
    ) {}

    public static function desdeArray(array $datos): self
    {
        return new self(
            nombre: $datos['nombre'] ?? null,
            subdominio: $datos['subdominio'] ?? null
        );
    }

    public function aArray(): array
    {
        $resultado = [];

        if ($this->nombre !== null) {
            $resultado['nombre'] = $this->nombre;
        }

        if ($this->subdominio !== null) {
            $resultado['subdominio'] = $this->subdominio;
        }

        return $resultado;
    }

    public function tieneCambios(): bool
    {
        return $this->nombre !== null ||
               $this->subdominio !== null;
    }
}
