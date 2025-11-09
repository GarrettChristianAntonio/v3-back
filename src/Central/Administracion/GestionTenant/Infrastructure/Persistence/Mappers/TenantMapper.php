<?php

declare(strict_types=1);

namespace Src\Central\Administracion\GestionTenant\Infrastructure\Persistence\Mappers;

use DateTimeImmutable;
use Src\Central\Administracion\GestionTenant\Domain\Entities\Tenant;
use Src\Central\Administracion\GestionTenant\Infrastructure\Persistence\Eloquent\TenantModel;
use Src\Central\Shared\Domain\ValueObjects\Subdominio;
use Src\Central\Shared\Domain\ValueObjects\Uuid;

/**
 * Mapper para convertir entre la Entidad de Dominio y el Modelo de Eloquent
 *
 * Este mapper es parte de la capa de Infrastructure y permite
 * mantener el Domain completamente independiente de Eloquent.
 */
final class TenantMapper
{
    /**
     * Convierte un modelo de Eloquent a una entidad de dominio
     */
    public static function toDomain(TenantModel $model): Tenant
    {
        return Tenant::reconstruir(
            id: new Uuid($model->id),
            nombre: $model->nombre,
            subdominio: new Subdominio($model->subdominio),
            datos: $model->datos ?? [],
            fechaCreacion: $model->created_at
                ? DateTimeImmutable::createFromMutable($model->created_at)
                : null,
            fechaActualizacion: $model->updated_at
                ? DateTimeImmutable::createFromMutable($model->updated_at)
                : null
        );
    }

    /**
     * Convierte una entidad de dominio a un modelo de Eloquent
     */
    public static function toModel(Tenant $entity): TenantModel
    {
        $model = new TenantModel();

        $model->id = $entity->id()->valor();
        $model->nombre = $entity->nombre();
        $model->subdominio = $entity->subdominio()->valor();
        $model->datos = $entity->datos();

        return $model;
    }

    /**
     * Actualiza un modelo existente con datos de una entidad
     */
    public static function updateModel(TenantModel $model, Tenant $entity): void
    {
        $model->nombre = $entity->nombre();
        $model->subdominio = $entity->subdominio()->valor();
        $model->datos = $entity->datos();
    }

    /**
     * Convierte un array de modelos a un array de entidades
     *
     * @param TenantModel[] $models
     * @return Tenant[]
     */
    public static function toDomainCollection(array $models): array
    {
        return array_map(
            fn(TenantModel $model) => self::toDomain($model),
            $models
        );
    }
}
