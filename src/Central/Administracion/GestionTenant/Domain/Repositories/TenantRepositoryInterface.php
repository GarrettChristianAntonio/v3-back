<?php

declare(strict_types=1);

namespace Src\Central\Administracion\GestionTenant\Domain\Repositories;

use Src\Central\Administracion\GestionTenant\Domain\Entities\Tenant;

/**
 * Interfaz del repositorio de Tenant (capa de Domain)
 *
 * Define el contrato para persistir y recuperar Tenants.
 * La implementación está en Infrastructure.
 */
interface TenantRepositoryInterface
{
    /**
     * Guarda una nueva entidad Tenant
     */
    public function save(Tenant $tenant): void;

    /**
     * Busca un Tenant por su subdominio
     */
    public function findBySubdomain(string $subdomain): ?Tenant;

    /**
     * Busca un Tenant por su ID
     */
    public function findById(string $id): ?Tenant;

    /**
     * Actualiza un Tenant existente
     */
    public function update(Tenant $tenant): void;

    /**
     * Elimina un Tenant
     */
    public function delete(string $id): bool;

    /**
     * Obtiene todos los Tenants
     *
     * @return Tenant[]
     */
    public function all(): array;

    /**
     * Verifica si existe un Tenant con el subdominio dado
     */
    public function existsBySubdomain(string $subdomain): bool;
}
