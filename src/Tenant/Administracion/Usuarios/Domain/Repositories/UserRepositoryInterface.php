<?php

declare(strict_types=1);

namespace Src\Tenant\Administracion\Usuarios\Domain\Repositories;

use Src\Tenant\Administracion\Usuarios\Domain\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data): User;

    public function findByNameuser(string $nameuser): ?User;

    public function findById(string $id): ?User;

    public function update(string $id, array $data): User;

    public function delete(string $id): bool;

    public function all();
}
