<?php

declare(strict_types=1);

namespace Src\Central\Administracion\Usuarios\Application\DTOs;

final class CreateUserDTO
{
    public function __construct(
        public readonly string $nameuser,
        public readonly string $password
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nameuser: $data['nameuser'] ?? '',
            password: $data['password'] ?? ''
        );
    }

    public function toArray(): array
    {
        return [
            'nameuser' => $this->nameuser,
            'password' => $this->password,
        ];
    }
}
