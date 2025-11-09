<?php

declare(strict_types=1);

namespace Src\Tenant\Administracion\Usuarios\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes;

    protected $connection = 'tenant';
    protected $table = 'usuarios';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nombre',
        'apellido',
        'nombre_usuario',
        'correo_electronico',
        'contrasena',
    ];

    protected $hidden = [
        'contrasena',
    ];

    protected $casts = [
        'contrasena' => 'hashed',
    ];

    /**
     * Obtener el nombre de la columna de contraseña.
     *
     * @return string
     */
    public function getAuthPasswordName()
    {
        return 'contrasena';
    }

    /**
     * Obtener la contraseña para autenticación.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
}
