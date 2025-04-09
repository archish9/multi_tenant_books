<?php

namespace App\Providers;

use App\Models\AdminUser;
use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Gate;

class TenantAwareFilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Configure Filament auth - remove this line as it's not correct in Filament 3.x
        // Filament::auth()->guard('web')->provider('users');
        
        // Add tenant awareness to Filament
        Filament::serving(function () {
            // Check if we're in a tenant context
            if (tenant()) {
                // Add tenant filter to all queries
                Gate::define('view-any-book', function (AdminUser $user) {
                    return $user->tenant_id === tenant('id');
                });
                
                Gate::define('create-book', function (AdminUser $user) {
                    return $user->tenant_id === tenant('id');
                });
                
                Gate::define('update-book', function (AdminUser $user) {
                    return $user->tenant_id === tenant('id');
                });
                
                Gate::define('delete-book', function (AdminUser $user) {
                    return $user->tenant_id === tenant('id');
                });
            }
        });
    }
}