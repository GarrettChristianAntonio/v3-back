<?php

declare(strict_types=1);

namespace Src\Tenant\Administracion\Usuarios\Infrastructure\Persistence;

use Src\Tenant\Administracion\Usuarios\Domain\Models\User;
use Src\Tenant\Administracion\Usuarios\Domain\Repositories\UserRepositoryInterface;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByNameuser(string $nombreUsuario): ?User
    {
        return User::where('nombre_usuario', $nombreUsuario)->first();
    }

    public function findById(string $id): ?User
    {
        return User::find($id);
    }

    public function update(string $id, array $data): User
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user->fresh();
    }

    public function delete(string $id): bool
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }

    public function all()
    {
        return User::all();
    }
}
