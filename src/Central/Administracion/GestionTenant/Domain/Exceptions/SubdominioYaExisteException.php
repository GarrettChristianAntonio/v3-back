<?php

declare(strict_types=1);

namespace Src\Central\Administracion\GestionTenant\Domain\Exceptions;

final class SubdominioYaExisteException extends TenantException
{
    public static function crear(string $subdominio): self
    {
        return new self("El subdominio '{$subdominio}' ya está en uso");
    }
}
