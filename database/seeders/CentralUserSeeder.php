<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Src\Central\Administracion\Usuarios\Domain\Models\User;

class CentralUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nombre_usuario' => 'admin',
            'contrasena' => Hash::make('admin'),
        ]);
    }
}
