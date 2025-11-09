<?php

declare(strict_types=1);

namespace Src\Central\Administracion\GestionTenant\Application\DTOs;

use Src\Central\Administracion\GestionTenant\Domain\Entities\Tenant;

final class RespuestaTenantDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $nombre,
        public readonly string $subdominio,
        public readonly string $creadoEn,
        public readonly string $actualizadoEn
    ) {}

    /**
     * Crea un DTO desde una entidad de dominio
     */
    public static function desdeEntidad(Tenant $tenant): self
    {
        return new self(
            id: $tenant->id()->valor(),
            nombre: $tenant->nombre(),
            subdominio: $tenant->subdominio()->valor(),
            creadoEn: $tenant->fechaCreacion()?->format(\DateTimeInterface::ATOM) ?? '',
            actualizadoEn: $tenant->fechaActualizacion()?->format(\DateTimeInterface::ATOM) ?? ''
        );
    }

    public function aArray(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'subdominio' => $this->subdominio,
            'creado_en' => $this->creadoEn,
            'actualizado_en' => $this->actualizadoEn,
        ];
    }
}
