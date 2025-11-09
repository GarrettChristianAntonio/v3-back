<?php

declare(strict_types=1);

namespace Src\Central\Administracion\GestionTenant\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

/**
 * Modelo de Eloquent para Tenant (capa de Infrastructure)
 * Este modelo NO debe ser usado fuera de la capa de Infrastructure
 */
class TenantModel extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, SoftDeletes;

    protected $connection = 'central';

    /**
     * Los atributos por defecto del modelo.
     *
     * @var array
     */
    protected $attributes = [
        'datos' => '{}',
    ];

    protected $fillable = [
        'id',
        'nombre',
        'subdominio',
        'datos',
    ];

    protected $casts = [
        'datos' => 'array',
    ];

    /**
     * Sobrescribir el método fill para mapear 'data' a 'datos'.
     */
    public function fill(array $attributes)
    {
        if (isset($attributes['data'])) {
            $attributes['datos'] = $attributes['data'];
            unset($attributes['data']);
        }

        return parent::fill($attributes);
    }

    /**
     * Mutator para 'data' que escribe en 'datos'.
     */
    public function setDataAttribute($value)
    {
        $this->attributes['datos'] = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Accessor para 'data' - alias de 'datos' para compatibilidad con Stancl.
     */
    public function getDataAttribute()
    {
        return $this->datos ?? [];
    }

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'nombre',
            'subdominio',
            'datos',
        ];
    }

    /**
     * Get the database configuration for this tenant
     */
    public function database(): \Stancl\Tenancy\DatabaseConfig
    {
        return new \Stancl\Tenancy\DatabaseConfig($this);
    }

    /**
     * Get the name of the unique identifier for the tenant.
     */
    public function getTenantKeyName(): string
    {
        return 'id';
    }

    /**
     * Get the value of the tenant's unique identifier.
     */
    public function getTenantKey(): string
    {
        return $this->getAttribute($this->getTenantKeyName());
    }
}
