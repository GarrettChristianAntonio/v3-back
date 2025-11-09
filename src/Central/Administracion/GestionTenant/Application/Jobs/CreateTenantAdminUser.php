<?php

declare(strict_types=1);

namespace Src\Central\Administracion\GestionTenant\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Src\Central\Administracion\GestionTenant\Domain\Models\Tenant;
use Src\Tenant\Administracion\Usuarios\Domain\Models\User;

class CreateTenantAdminUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Tenant $tenant
    ) {}

    public function handle(): void
    {
        // Obtener datos del usuario admin desde el campo datos del tenant
        $datos = $this->tenant->datos ?? [];
        $adminData = $datos['admin'] ?? [];

        if (empty($adminData['nombre']) || empty($adminData['apellido']) ||
            empty($adminData['correo_electronico']) || empty($adminData['contrasena'])) {
            return; // No crear usuario si faltan datos
        }

        // Inicializar el contexto del tenant
        tenancy()->initialize($this->tenant);

        // Crear el usuario admin en la base de datos del tenant
        User::create([
            'nombre' => $adminData['nombre'],
            'apellido' => $adminData['apellido'],
            'nombre_usuario' => $adminData['nombre_usuario'] ?? 'admin',
            'correo_electronico' => $adminData['correo_electronico'],
            'contrasena' => Hash::make($adminData['contrasena']),
        ]);

        // Terminar el contexto del tenant
        tenancy()->end();
    }
}
