<?php

declare(strict_types=1);

namespace Src\Central\Shared\Domain\ValueObjects;

use InvalidArgumentException;

final class Subdominio
{
    private const LONGITUD_MINIMA = 3;
    private const LONGITUD_MAXIMA = 63;
    private const PATRON = '/^[a-z0-9-]+$/';

    private string $valor;

    public function __construct(string $valor)
    {
        $this->validar($valor);
        $this->valor = strtolower(trim($valor));
    }

    private function validar(string $valor): void
    {
        $valorLimpio = strtolower(trim($valor));

        if (empty($valorLimpio)) {
            throw new InvalidArgumentException('El subdominio no puede estar vacío');
        }

        $longitud = strlen($valorLimpio);

        if ($longitud < self::LONGITUD_MINIMA) {
            throw new InvalidArgumentException(
                sprintf('El subdominio debe tener al menos %d caracteres', self::LONGITUD_MINIMA)
            );
        }

        if ($longitud > self::LONGITUD_MAXIMA) {
            throw new InvalidArgumentException(
                sprintf('El subdominio no puede exceder %d caracteres', self::LONGITUD_MAXIMA)
            );
        }

        if (!preg_match(self::PATRON, $valorLimpio)) {
            throw new InvalidArgumentException(
                'El subdominio solo puede contener letras minúsculas, números y guiones'
            );
        }

        if (str_starts_with($valorLimpio, '-') || str_ends_with($valorLimpio, '-')) {
            throw new InvalidArgumentException(
                'El subdominio no puede comenzar ni terminar con guiones'
            );
        }
    }

    public function valor(): string
    {
        return $this->valor;
    }

    public function equals(Subdominio $otro): bool
    {
        return $this->valor === $otro->valor;
    }

    public function __toString(): string
    {
        return $this->valor;
    }
}
