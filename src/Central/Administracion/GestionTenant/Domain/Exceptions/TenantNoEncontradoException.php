<?php

declare(strict_types=1);

namespace Src\Central\Administracion\GestionTenant\Domain\Exceptions;

final class TenantNoEncontradoException extends TenantException
{
    public static function conId(string $id): self
    {
        return new self("No se encontró el tenant con ID: {$id}");
    }

    public static function conSubdominio(string $subdominio): self
    {
        return new self("No se encontró el tenant con subdominio: {$subdominio}");
    }
}
