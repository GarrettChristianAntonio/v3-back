<?php

declare(strict_types=1);

namespace Src\Central\Administracion\GestionTenant\Infrastructure\Persistence;

use Src\Central\Administracion\GestionTenant\Domain\Entities\Tenant;
use Src\Central\Administracion\GestionTenant\Domain\Repositories\TenantRepositoryInterface;
use Src\Central\Administracion\GestionTenant\Domain\Exceptions\TenantNoEncontradoException;
use Src\Central\Administracion\GestionTenant\Infrastructure\Persistence\Eloquent\TenantModel;
use Src\Central\Administracion\GestionTenant\Infrastructure\Persistence\Eloquent\DomainModel;
use Src\Central\Administracion\GestionTenant\Infrastructure\Persistence\Mappers\TenantMapper;

/**
 * Implementación del repositorio usando Eloquent
 *
 * Esta clase pertenece a la capa de Infrastructure y se encarga de:
 * - Convertir entre entidades de dominio y modelos de Eloquent usando el Mapper
 * - Manejar la persistencia con la base de datos
 */
class EloquentTenantRepository implements TenantRepositoryInterface
{
    public function save(Tenant $tenant): void
    {
        $model = TenantMapper::toModel($tenant);
        $model->save();

        // Crear el dominio asociado
        foreach ($tenant->dominios() as $dominio) {
            $domainModel = new DomainModel();
            $domainModel->tenant_id = $tenant->id()->valor();
            $domainModel->dominio = $dominio;
            $domainModel->save();
        }
    }

    public function findBySubdomain(string $subdomain): ?Tenant
    {
        $model = TenantModel::where('subdominio', $subdomain)->first();

        return $model ? TenantMapper::toDomain($model) : null;
    }

    public function findById(string $id): ?Tenant
    {
        $model = TenantModel::find($id);

        return $model ? TenantMapper::toDomain($model) : null;
    }

    public function update(Tenant $tenant): void
    {
        $model = TenantModel::findOrFail($tenant->id()->valor());

        TenantMapper::updateModel($model, $tenant);
        $model->save();
    }

    public function delete(string $id): bool
    {
        $model = TenantModel::find($id);

        if (!$model) {
            throw TenantNoEncontradoException::conId($id);
        }

        return $model->delete();
    }

    public function all(): array
    {
        $models = TenantModel::all()->all(); // Convierte Collection a array de modelos

        return TenantMapper::toDomainCollection($models);
    }

    public function existsBySubdomain(string $subdomain): bool
    {
        return TenantModel::where('subdominio', $subdomain)->exists();
    }
}
