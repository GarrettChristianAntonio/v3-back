<?php

declare(strict_types=1);

namespace Src\Tenant\Shared\Application\DTOs;

final class AuthTokenResponseDTO
{
    public function __construct(
        public readonly array $usuario,
        public readonly string $token,
        public readonly string $tipoToken = 'Bearer'
    ) {}

    public static function crear(array $usuario, string $token): self
    {
        return new self(
            usuario: $usuario,
            token: $token,
            tipoToken: 'Bearer'
        );
    }

    public function aArray(): array
    {
        return [
            'usuario' => $this->usuario,
            'token' => $this->token,
            'tipo_token' => $this->tipoToken,
        ];
    }
}
