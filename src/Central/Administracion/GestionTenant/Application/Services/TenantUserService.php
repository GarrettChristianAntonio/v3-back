<?php

declare(strict_types=1);

namespace Src\Central\Administracion\GestionTenant\Application\Services;

use Illuminate\Support\Facades\Hash;
use Src\Central\Administracion\GestionTenant\Application\DTOs\CrearUsuarioTenantDTO;
use Src\Central\Administracion\GestionTenant\Domain\Exceptions\TenantNoEncontradoException;
use Src\Central\Administracion\GestionTenant\Infrastructure\Persistence\Eloquent\TenantModel;
use Src\Central\Shared\Domain\ValueObjects\Uuid;
use Src\Tenant\Administracion\Usuarios\Domain\Models\User;

/**
 * Application Service para crear usuarios en tenants
 *
 * NOTA: Este servicio cruza bounded contexts (Central → Tenant).
 * En una arquitectura más madura, esto debería hacerse mediante eventos de dominio.
 */
class TenantUserService
{
    /**
     * Crear un usuario en la base de datos de un tenant específico
     */
    public function crearUsuarioEnTenant(CrearUsuarioTenantDTO $dto): array
    {
        try {
            // Validar que el tenant existe usando el modelo de Eloquent
            // (necesario para tenancy()->initialize())
            $uuid = new Uuid($dto->tenantId);
            $tenantModel = TenantModel::find($uuid->valor());

            if (!$tenantModel) {
                throw TenantNoEncontradoException::conId($dto->tenantId);
            }

            // Inicializar el contexto del tenant (requiere modelo de Eloquent)
            tenancy()->initialize($tenantModel);

            // Crear el usuario en la base de datos del tenant
            $user = User::create([
                'nombre' => $dto->nombre,
                'apellido' => $dto->apellido,
                'nombre_usuario' => $dto->nombreUsuario,
                'correo_electronico' => $dto->correoElectronico,
                'contrasena' => Hash::make($dto->contrasena),
            ]);

            // Terminar el contexto del tenant
            tenancy()->end();

            return [
                'id' => $user->id,
                'nombre' => $user->nombre,
                'apellido' => $user->apellido,
                'nombre_usuario' => $user->nombre_usuario,
                'correo_electronico' => $user->correo_electronico,
                'tenant_id' => $dto->tenantId,
            ];
        } catch (\Exception $e) {
            // Asegurarse de terminar el contexto del tenant en caso de error
            if (tenancy()->initialized) {
                tenancy()->end();
            }
            throw $e;
        }
    }
}
