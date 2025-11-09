<?php

declare(strict_types=1);

namespace Src\Central\Administracion\GestionTenant\Application\Services;

use Illuminate\Support\Str;
use Src\Central\Administracion\GestionTenant\Domain\Entities\Tenant;
use Src\Central\Administracion\GestionTenant\Domain\Repositories\TenantRepositoryInterface;
use Src\Central\Administracion\GestionTenant\Domain\Exceptions\SubdominioYaExisteException;
use Src\Central\Administracion\GestionTenant\Domain\Exceptions\TenantNoEncontradoException;
use Src\Central\Administracion\GestionTenant\Application\DTOs\CrearTenantDTO;
use Src\Central\Administracion\GestionTenant\Application\DTOs\ActualizarTenantDTO;
use Src\Central\Administracion\GestionTenant\Application\DTOs\RespuestaTenantDTO;
use Src\Central\Shared\Domain\ValueObjects\Subdominio;
use Src\Central\Shared\Domain\ValueObjects\Uuid;
use Illuminate\Support\Facades\DB;

/**
 * Application Service para gestionar Tenants
 *
 * Orquesta casos de uso relacionados con Tenants.
 * La lógica de negocio está en la entidad Tenant, este servicio solo coordina.
 */
class TenantService
{
    public function __construct(
        private TenantRepositoryInterface $tenantRepository
    ) {}

    /**
     * Crear un nuevo tenant con su base de datos
     */
    public function crearTenant(CrearTenantDTO $dto): RespuestaTenantDTO
    {
        DB::connection('central')->beginTransaction();

        try {
            $subdominio = new Subdominio($dto->subdominio);

            // Verificar unicidad del subdominio (regla de dominio)
            if ($this->tenantRepository->existsBySubdomain($subdominio->valor())) {
                throw SubdominioYaExisteException::crear($subdominio->valor());
            }

            // Generar UUID para el tenant
            $tenantId = new Uuid(Str::uuid()->toString());

            // Usar el factory method de la entidad para crear el tenant
            // La lógica de negocio está en la entidad
            $tenant = Tenant::crear(
                id: $tenantId,
                nombre: $dto->nombre,
                subdominio: $subdominio
            );

            // Persistir la entidad
            $this->tenantRepository->save($tenant);

            DB::connection('central')->commit();

            return RespuestaTenantDTO::desdeEntidad($tenant);
        } catch (\Exception $e) {
            DB::connection('central')->rollBack();
            throw $e;
        }
    }

    /**
     * Obtener tenant por subdominio
     */
    public function obtenerTenantPorSubdominio(string $subdominio): ?RespuestaTenantDTO
    {
        $subdomainVO = new Subdominio($subdominio);
        $tenant = $this->tenantRepository->findBySubdomain($subdomainVO->valor());

        return $tenant ? RespuestaTenantDTO::desdeEntidad($tenant) : null;
    }

    /**
     * Obtener tenant por ID
     */
    public function obtenerTenantPorId(string $id): ?RespuestaTenantDTO
    {
        $uuid = new Uuid($id);
        $tenant = $this->tenantRepository->findById($uuid->valor());

        return $tenant ? RespuestaTenantDTO::desdeEntidad($tenant) : null;
    }

    /**
     * Actualizar información del tenant
     */
    public function actualizarTenant(string $id, ActualizarTenantDTO $dto): RespuestaTenantDTO
    {
        DB::connection('central')->beginTransaction();

        try {
            $uuid = new Uuid($id);

            // Obtener la entidad
            $tenant = $this->tenantRepository->findById($uuid->valor());

            if (!$tenant) {
                throw TenantNoEncontradoException::conId($id);
            }

            // Si se está actualizando el subdominio, verificar unicidad
            if ($dto->subdominio !== null) {
                $nuevoSubdominio = new Subdominio($dto->subdominio);

                if ($this->tenantRepository->existsBySubdomain($nuevoSubdominio->valor())) {
                    $existente = $this->tenantRepository->findBySubdomain($nuevoSubdominio->valor());
                    if ($existente && !$existente->esIgualA($tenant)) {
                        throw SubdominioYaExisteException::crear($nuevoSubdominio->valor());
                    }
                }
            }

            // Usar el método de la entidad para actualizar (lógica de negocio)
            $tenant->actualizar(
                nombre: $dto->nombre,
                subdominio: $dto->subdominio ? new Subdominio($dto->subdominio) : null
            );

            // Persistir los cambios
            $this->tenantRepository->update($tenant);

            DB::connection('central')->commit();

            return RespuestaTenantDTO::desdeEntidad($tenant);
        } catch (\Exception $e) {
            DB::connection('central')->rollBack();
            throw $e;
        }
    }

    /**
     * Eliminar tenant (soft delete)
     */
    public function eliminarTenant(string $id): bool
    {
        $uuid = new Uuid($id);

        // Obtener la entidad para validar reglas de negocio
        $tenant = $this->tenantRepository->findById($uuid->valor());

        if (!$tenant) {
            throw TenantNoEncontradoException::conId($id);
        }

        // Verificar si puede ser eliminado (regla de negocio en la entidad)
        if (!$tenant->puedeSerEliminado()) {
            throw new \DomainException('El tenant no puede ser eliminado en este momento');
        }

        return $this->tenantRepository->delete($uuid->valor());
    }

    /**
     * Obtener todos los tenants
     */
    public function obtenerTodosLosTenants(): array
    {
        $tenants = $this->tenantRepository->all();

        return array_map(
            fn(Tenant $tenant) => RespuestaTenantDTO::desdeEntidad($tenant),
            $tenants
        );
    }
}
