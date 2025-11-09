<?php

declare(strict_types=1);

namespace Src\Tenant\Administracion\Usuarios\Application\DTOs;

final class UserResponseDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $lastname,
        public readonly string $nameuser,
        public readonly string $mail,
        public readonly string $at,
        public readonly string $created_at,
        public readonly string $updated_at
    ) {}

    public static function fromModel($user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            lastname: $user->lastname,
            nameuser: $user->nameuser,
            mail: $user->mail,
            at: $user->at?->toISOString() ?? '',
            created_at: $user->created_at?->toISOString() ?? '',
            updated_at: $user->updated_at?->toISOString() ?? ''
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'nameuser' => $this->nameuser,
            'mail' => $this->mail,
            'at' => $this->at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
