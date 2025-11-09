<?php

declare(strict_types=1);

namespace Src\Tenant\Administracion\Usuarios\Application\DTOs;

final class CreateUserDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $lastname,
        public readonly string $nameuser,
        public readonly string $mail,
        public readonly string $password
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? '',
            lastname: $data['lastname'] ?? '',
            nameuser: $data['nameuser'] ?? '',
            mail: $data['mail'] ?? '',
            password: $data['password'] ?? ''
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'lastname' => $this->lastname,
            'nameuser' => $this->nameuser,
            'mail' => $this->mail,
            'password' => $this->password,
        ];
    }
}
