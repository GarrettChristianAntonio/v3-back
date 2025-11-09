<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Src\Tenant\Administracion\Usuarios\Domain\Models\User;

class TenantUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenant = tenant();
        $tenantName = $tenant ? $tenant->subdominio : 'tenant';

        User::create([
            'name' => 'Admin',
            'lastname' => ucfirst($tenantName),
            'nameuser' => 'admin' . $tenantName,
            'mail' => 'admin@' . $tenantName . '.com',
            'password' => Hash::make('admin' . $tenantName),
        ]);
    }
}
