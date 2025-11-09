<?php

declare(strict_types=1);

namespace Src\Central\Shared\Domain\ValueObjects;

use InvalidArgumentException;

final class Uuid
{
    private string $valor;

    public function __construct(string $valor)
    {
        $this->validar($valor);
        $this->valor = $valor;
    }

    private function validar(string $valor): void
    {
        if (empty($valor)) {
            throw new InvalidArgumentException('El UUID no puede estar vacío');
        }

        $patron = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';

        if (!preg_match($patron, $valor)) {
            throw new InvalidArgumentException('El formato del UUID no es válido');
        }
    }

    public static function generar(callable $generador): self
    {
        $valor = $generador();
        return new self($valor);
    }

    public function valor(): string
    {
        return $this->valor;
    }

    public function equals(Uuid $otro): bool
    {
        return $this->valor === $otro->valor;
    }

    public function __toString(): string
    {
        return $this->valor;
    }
}
