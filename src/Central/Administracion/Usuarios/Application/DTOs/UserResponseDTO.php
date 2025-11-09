<?php

declare(strict_types=1);

namespace Src\Central\Administracion\Usuarios\Application\DTOs;

final class UserResponseDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $nameuser,
        public readonly string $date_at,
        public readonly string $created_at,
        public readonly string $updated_at
    ) {}

    public static function fromModel($user): self
    {
        return new self(
            id: $user->id,
            nameuser: $user->nameuser,
            date_at: $user->date_at?->toISOString() ?? '',
            created_at: $user->created_at?->toISOString() ?? '',
            updated_at: $user->updated_at?->toISOString() ?? ''
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nameuser' => $this->nameuser,
            'date_at' => $this->date_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
