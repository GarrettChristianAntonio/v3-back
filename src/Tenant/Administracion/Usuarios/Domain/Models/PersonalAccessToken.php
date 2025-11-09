<?php

declare(strict_types=1);

namespace Src\Tenant\Administracion\Usuarios\Domain\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    /**
     * La conexión de base de datos que debe usar el modelo.
     *
     * @var string|null
     */
    protected $connection = 'tenant';

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'tokens_acceso_personal';

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'token',
        'habilidades',
        'ultimo_uso_en',
        'expira_en',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'habilidades' => 'json',
        'ultimo_uso_en' => 'datetime',
        'expira_en' => 'datetime',
    ];

    /**
     * Sobrescribir el método fill para mapear 'name' a 'nombre'.
     */
    public function fill(array $attributes)
    {
        if (isset($attributes['name'])) {
            $attributes['nombre'] = $attributes['name'];
            unset($attributes['name']);
        }

        if (isset($attributes['abilities'])) {
            $attributes['habilidades'] = $attributes['abilities'];
            unset($attributes['abilities']);
        }

        if (isset($attributes['last_used_at'])) {
            $attributes['ultimo_uso_en'] = $attributes['last_used_at'];
            unset($attributes['last_used_at']);
        }

        if (isset($attributes['expires_at'])) {
            $attributes['expira_en'] = $attributes['expires_at'];
            unset($attributes['expires_at']);
        }

        return parent::fill($attributes);
    }

    /**
     * Obtener el nombre del atributo "name".
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->attributes['nombre'] ?? null;
    }

    /**
     * Establecer el nombre del atributo "name".
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['nombre'] = $value;
    }

    /**
     * Obtener el valor del atributo "abilities".
     *
     * @return array|null
     */
    public function getAbilitiesAttribute()
    {
        return $this->attributes['habilidades'] ?? null;
    }

    /**
     * Establecer el valor del atributo "abilities".
     *
     * @param  array|null  $value
     * @return void
     */
    public function setAbilitiesAttribute($value)
    {
        $this->attributes['habilidades'] = $value;
    }

    /**
     * Obtener el valor del atributo "last_used_at".
     *
     * @return \Illuminate\Support\Carbon|null
     */
    public function getLastUsedAtAttribute()
    {
        return $this->attributes['ultimo_uso_en'] ?? null;
    }

    /**
     * Establecer el valor del atributo "last_used_at".
     *
     * @param  mixed  $value
     * @return void
     */
    public function setLastUsedAtAttribute($value)
    {
        $this->attributes['ultimo_uso_en'] = $value;
    }

    /**
     * Obtener el valor del atributo "expires_at".
     *
     * @return \Illuminate\Support\Carbon|null
     */
    public function getExpiresAtAttribute()
    {
        return $this->attributes['expira_en'] ?? null;
    }

    /**
     * Establecer el valor del atributo "expires_at".
     *
     * @param  mixed  $value
     * @return void
     */
    public function setExpiresAtAttribute($value)
    {
        $this->attributes['expira_en'] = $value;
    }
}
