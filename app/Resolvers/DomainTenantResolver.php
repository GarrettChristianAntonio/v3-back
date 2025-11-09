<?php

declare(strict_types=1);

namespace App\Resolvers;

use Illuminate\Database\Eloquent\Builder;
use Stancl\Tenancy\Contracts\Domain;
use Stancl\Tenancy\Contracts\Tenant;
use Stancl\Tenancy\Resolvers\DomainTenantResolver as BaseDomainTenantResolver;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException;

class DomainTenantResolver extends BaseDomainTenantResolver
{
    /**
     * Sobrescribir el método resolveWithoutCache para usar 'dominio' en lugar de 'domain'.
     */
    public function resolveWithoutCache(...$args): Tenant
    {
        $domain = $args[0];

        /** @var Tenant|null $tenant */
        $tenant = config('tenancy.tenant_model')::query()
            ->whereHas('domains', function (Builder $query) use ($domain) {
                // Usar 'dominio' en lugar de 'domain'
                $query->where('dominio', $domain);
            })
            ->with('domains')
            ->first();

        if ($tenant) {
            $this->setCurrentDomain($tenant, $domain);

            return $tenant;
        }

        throw new TenantCouldNotBeIdentifiedOnDomainException($args[0]);
    }

    /**
     * Sobrescribir setCurrentDomain para usar 'dominio' en lugar de 'domain'.
     */
    protected function setCurrentDomain(Tenant $tenant, string $domain): void
    {
        // Usar 'dominio' en lugar de 'domain'
        static::$currentDomain = $tenant->domains->where('dominio', $domain)->first();
    }

    /**
     * Sobrescribir getArgsForTenant para usar 'dominio' en lugar de 'domain'.
     */
    public function getArgsForTenant(Tenant $tenant): array
    {
        $tenant->unsetRelation('domains');

        return $tenant->domains->map(function (Domain $domain) {
            // Acceder al atributo 'dominio' correctamente
            return [$domain->dominio];
        })->toArray();
    }
}
