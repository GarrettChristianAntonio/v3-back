<?php

declare(strict_types=1);

namespace Src\Central\Administracion\GestionTenant\Infrastructure\Persistence\Eloquent;

use Stancl\Tenancy\Database\Models\Domain as BaseDomain;

/**
 * Modelo de Eloquent para Domain (capa de Infrastructure)
 */
class DomainModel extends BaseDomain
{
    /**
     * La conexión de base de datos que debe usar el modelo.
     *
     * @var string
     */
    protected $connection = 'central';

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'dominios';

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'dominio',
        'tenant_id',
    ];

    /**
     * Sobrescribir el método fill para mapear 'domain' a 'dominio'.
     */
    public function fill(array $attributes)
    {
        if (isset($attributes['domain'])) {
            $attributes['dominio'] = $attributes['domain'];
            unset($attributes['domain']);
        }

        return parent::fill($attributes);
    }

    /**
     * Accessor para 'domain' que lee de 'dominio'.
     */
    public function getDomainAttribute()
    {
        return $this->attributes['dominio'] ?? null;
    }

    /**
     * Mutator para 'domain' que escribe en 'dominio'.
     */
    public function setDomainAttribute($value)
    {
        $this->attributes['dominio'] = $value;
    }

    /**
     * Scope para buscar por dominio - sobrescribe el scope de Stancl.
     */
    public function scopeWhereDomain($query, $domain)
    {
        return $query->where('dominio', $domain);
    }

    /**
     * Obtener la clave del dominio (nombre de la columna).
     */
    public function getDomainKey(): string
    {
        return 'dominio';
    }

    /**
     * Sobrescribir el boot del trait ConvertsDomainsToLowercase para usar 'dominio'.
     */
    public static function bootConvertsDomainsToLowercase()
    {
        static::saving(function ($model) {
            $model->dominio = strtolower($model->dominio);
        });
    }

    /**
     * Sobrescribir el boot del trait para usar 'dominio' en lugar de 'domain'.
     */
    public static function bootEnsuresDomainIsNotOccupied()
    {
        static::saving(function ($self) {
            if ($domain = $self->newQuery()->where('dominio', $self->dominio)->first()) {
                if ($domain->getKey() !== $self->getKey()) {
                    throw new \Stancl\Tenancy\Exceptions\DomainOccupiedByOtherTenantException($self->dominio);
                }
            }
        });
    }

    /**
     * Relación con el tenant.
     */
    public function tenant()
    {
        return $this->belongsTo(config('tenancy.tenant_model'), 'tenant_id');
    }
}
