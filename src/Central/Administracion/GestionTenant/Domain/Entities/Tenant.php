<?php

declare(strict_types=1);

namespace Src\Central\Administracion\GestionTenant\Domain\Entities;

use DateTimeImmutable;
use Src\Central\Shared\Domain\ValueObjects\Subdominio;
use Src\Central\Shared\Domain\ValueObjects\Uuid;
use Src\Central\Administracion\GestionTenant\Domain\Exceptions\SubdominioYaExisteException;

/**
 * Entidad de Dominio Tenant (Aggregate Root)
 *
 * Representa un tenant en el sistema multi-tenant.
 * Es completamente independiente de la infraestructura (sin Eloquent).
 */
final class Tenant
{
    private array $dominios = [];
    private ?DateTimeImmutable $fechaCreacion = null;
    private ?DateTimeImmutable $fechaActualizacion = null;

    private function __construct(
        private Uuid $id,
        private string $nombre,
        private Subdominio $subdominio,
        private array $datos = []
    ) {
        $this->fechaCreacion = new DateTimeImmutable();
        $this->fechaActualizacion = new DateTimeImmutable();
    }

    /**
     * Factory method para crear un nuevo Tenant
     */
    public static function crear(
        Uuid $id,
        string $nombre,
        Subdominio $subdominio
    ): self {
        if (empty($nombre)) {
            throw new \InvalidArgumentException('El nombre del tenant no puede estar vacío');
        }

        $tenant = new self(
            id: $id,
            nombre: $nombre,
            subdominio: $subdominio
        );

        // Al crear un tenant, automáticamente se crea su dominio principal
        $tenant->agregarDominioPrincipal();

        return $tenant;
    }

    /**
     * Factory method para reconstruir un Tenant desde persistencia
     */
    public static function reconstruir(
        Uuid $id,
        string $nombre,
        Subdominio $subdominio,
        array $datos = [],
        ?DateTimeImmutable $fechaCreacion = null,
        ?DateTimeImmutable $fechaActualizacion = null
    ): self {
        $tenant = new self(
            id: $id,
            nombre: $nombre,
            subdominio: $subdominio,
            datos: $datos
        );

        if ($fechaCreacion) {
            $tenant->fechaCreacion = $fechaCreacion;
        }
        if ($fechaActualizacion) {
            $tenant->fechaActualizacion = $fechaActualizacion;
        }

        return $tenant;
    }

    /**
     * Lógica de negocio: Agregar el dominio principal del tenant
     */
    private function agregarDominioPrincipal(): void
    {
        $dominioCompleto = $this->subdominio->valor() . '.sidis.com';
        $this->dominios[] = $dominioCompleto;
    }

    /**
     * Lógica de negocio: Actualizar información del tenant
     */
    public function actualizar(
        ?string $nombre = null,
        ?Subdominio $subdominio = null
    ): void {
        if ($nombre !== null) {
            if (empty($nombre)) {
                throw new \InvalidArgumentException('El nombre del tenant no puede estar vacío');
            }
            $this->nombre = $nombre;
        }

        if ($subdominio !== null) {
            $this->subdominio = $subdominio;
        }

        $this->fechaActualizacion = new DateTimeImmutable();
    }

    /**
     * Lógica de negocio: Verificar si el tenant puede ser eliminado
     */
    public function puedeSerEliminado(): bool
    {
        // Aquí podrías agregar lógica como:
        // - Verificar si tiene usuarios activos
        // - Verificar si tiene datos importantes
        // - Verificar si tiene suscripción activa
        return true; // Por ahora simple
    }

    /**
     * Lógica de negocio: Agregar datos personalizados
     */
    public function agregarDatos(string $clave, mixed $valor): void
    {
        $this->datos[$clave] = $valor;
        $this->fechaActualizacion = new DateTimeImmutable();
    }

    /**
     * Obtener datos personalizados
     */
    public function obtenerDato(string $clave): mixed
    {
        return $this->datos[$clave] ?? null;
    }

    // Getters (solo lectura desde fuera del agregado)

    public function id(): Uuid
    {
        return $this->id;
    }

    public function nombre(): string
    {
        return $this->nombre;
    }

    public function subdominio(): Subdominio
    {
        return $this->subdominio;
    }

    public function datos(): array
    {
        return $this->datos;
    }

    public function dominios(): array
    {
        return $this->dominios;
    }

    public function fechaCreacion(): ?DateTimeImmutable
    {
        return $this->fechaCreacion;
    }

    public function fechaActualizacion(): ?DateTimeImmutable
    {
        return $this->fechaActualizacion;
    }

    /**
     * Comparación de identidad
     */
    public function esIgualA(Tenant $otro): bool
    {
        return $this->id->valor() === $otro->id()->valor();
    }
}
