<?php

namespace App\Services;

use App\Models\Tenant;

class TenantService
{
    /**
     * Create a new tenant with domain
     *
     * @param string $name Tenant name
     * @param string $domain Tenant domain
     * @param string $email Tenant admin email
     * @return Tenant
     */
    public function create(string $name, string $domain, string $email): Tenant
    {
        $tenant = Tenant::create([
            'name' => $name,
            'email' => $email,
        ]);

        // Create domain for tenant
        $tenant->domains()->create([
            'domain' => $domain,
        ]);

        return $tenant;
    }

    /**
     * Initialize tenant database
     *
     * @param Tenant $tenant
     * @return void
     */
    public function initializeTenant(Tenant $tenant): void
    {
        // Runs migrations for the tenant
        $tenant->run(function () {
            // Artisan commands here are executed in the tenant context
            \Artisan::call('migrate', [
                '--path' => '/database/migrations/tenant',
                '--force' => true,
            ]);
        });
    }
}