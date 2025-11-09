<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Resolvers\DomainTenantResolver;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain as BaseInitializeTenancyByDomain;

class InitializeTenancyByDomain extends BaseInitializeTenancyByDomain
{
    /**
     * Resolver personalizado que usa la columna 'dominio'.
     *
     * @var string
     */
    public static string $tenantResolver = DomainTenantResolver::class;
}
