<?php

declare(strict_types=1);

namespace Src\Tenant\Shared\Domain\ValueObjects;

use InvalidArgumentException;

final class CorreoElectronico
{
    private string $valor;

    public function __construct(string $valor)
    {
        $this->validar($valor);
        $this->valor = strtolower(trim($valor));
    }

    private function validar(string $valor): void
    {
        if (empty($valor)) {
            throw new InvalidArgumentException('El correo electrónico no puede estar vacío');
        }

        if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('El formato del correo electrónico no es válido');
        }
    }

    public function valor(): string
    {
        return $this->valor;
    }

    public function equals(CorreoElectronico $otro): bool
    {
        return $this->valor === $otro->valor;
    }

    public function __toString(): string
    {
        return $this->valor;
    }
}
