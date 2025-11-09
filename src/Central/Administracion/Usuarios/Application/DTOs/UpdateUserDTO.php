<?php

declare(strict_types=1);

namespace Src\Central\Administracion\Usuarios\Application\DTOs;

final class UpdateUserDTO
{
    public function __construct(
        public readonly ?string $nameuser = null,
        public readonly ?string $password = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            nameuser: $data['nameuser'] ?? null,
            password: $data['password'] ?? null
        );
    }

    public function toArray(): array
    {
        $result = [];

        if ($this->nameuser !== null) {
            $result['nameuser'] = $this->nameuser;
        }

        if ($this->password !== null) {
            $result['password'] = $this->password;
        }

        return $result;
    }

    public function hasChanges(): bool
    {
        return $this->nameuser !== null || $this->password !== null;
    }
}
