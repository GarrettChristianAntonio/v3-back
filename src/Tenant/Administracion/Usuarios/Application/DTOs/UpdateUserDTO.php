<?php

declare(strict_types=1);

namespace Src\Tenant\Administracion\Usuarios\Application\DTOs;

final class UpdateUserDTO
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $lastname = null,
        public readonly ?string $nameuser = null,
        public readonly ?string $mail = null,
        public readonly ?string $password = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            lastname: $data['lastname'] ?? null,
            nameuser: $data['nameuser'] ?? null,
            mail: $data['mail'] ?? null,
            password: $data['password'] ?? null
        );
    }

    public function toArray(): array
    {
        $result = [];

        if ($this->name !== null) {
            $result['name'] = $this->name;
        }

        if ($this->lastname !== null) {
            $result['lastname'] = $this->lastname;
        }

        if ($this->nameuser !== null) {
            $result['nameuser'] = $this->nameuser;
        }

        if ($this->mail !== null) {
            $result['mail'] = $this->mail;
        }

        if ($this->password !== null) {
            $result['password'] = $this->password;
        }

        return $result;
    }

    public function hasChanges(): bool
    {
        return $this->name !== null ||
               $this->lastname !== null ||
               $this->nameuser !== null ||
               $this->mail !== null ||
               $this->password !== null;
    }
}
