<?php

namespace App\Console\Commands;

use App\Models\AdminUser;
use App\Services\TenantService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create {name} {domain} {email} {--admin-email=} {--admin-password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new tenant with admin user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(TenantService $tenantService)
    {
        $name = $this->argument('name');
        $domain = $this->argument('domain');
        $email = $this->argument('email');
        
        $adminEmail = $this->option('admin-email') ?: $email;
        $adminPassword = $this->option('admin-password') ?: 'password';

        $this->info("Creating tenant: {$name} with domain {$domain}");
        
        $tenant = $tenantService->create($name, $domain, $email);
        $this->info("Tenant created with ID: {$tenant->id}");
        
        $this->info("Running migrations for tenant {$tenant->id}");
        $tenantService->initializeTenant($tenant);
        
        $this->info("Creating admin user: {$adminEmail}");
        
        $tenant->run(function () use ($tenant, $adminEmail, $adminPassword) {
            AdminUser::create([
                'name' => 'Admin',
                'email' => $adminEmail,
                'password' => Hash::make($adminPassword),
                'tenant_id' => $tenant->id,
            ]);
        });
        
        $this->info("Admin user created successfully");
        $this->info("You can now access the admin panel at: https://{$domain}/admin");
        
        return 0;
    }
}