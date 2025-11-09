<?php

declare(strict_types=1);

namespace Src\Central\Shared\Domain\ValueObjects;

use InvalidArgumentException;

final class NombreUsuario
{
    private const LONGITUD_MINIMA = 3;
    private const LONGITUD_MAXIMA = 255;

    private string $valor;

    public function __construct(string $valor)
    {
        $this->validar($valor);
        $this->valor = trim($valor);
    }

    private function validar(string $valor): void
    {
        $valorLimpio = trim($valor);

        if (empty($valorLimpio)) {
            throw new InvalidArgumentException('El nombre de usuario no puede estar vacío');
        }

        $longitud = mb_strlen($valorLimpio);

        if ($longitud < self::LONGITUD_MINIMA) {
            throw new InvalidArgumentException(
                sprintf('El nombre de usuario debe tener al menos %d caracteres', self::LONGITUD_MINIMA)
            );
        }

        if ($longitud > self::LONGITUD_MAXIMA) {
            throw new InvalidArgumentException(
                sprintf('El nombre de usuario no puede exceder %d caracteres', self::LONGITUD_MAXIMA)
            );
        }
    }

    public function valor(): string
    {
        return $this->valor;
    }

    public function equals(NombreUsuario $otro): bool
    {
        return $this->valor === $otro->valor;
    }

    public function __toString(): string
    {
        return $this->valor;
    }
}
