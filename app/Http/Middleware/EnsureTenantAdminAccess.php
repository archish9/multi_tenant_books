<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureTenantAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if we're in a tenant context
        if (tenant()) {
            $user = Auth::user();

            // If user is associated with a different tenant, deny access
            if ($user && $user->tenant_id && $user->tenant_id !== tenant('id')) {
                abort(403, 'You are not authorized to access this tenant.');
            }
        }

        return $next($request);
    }
}