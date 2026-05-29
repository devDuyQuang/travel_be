<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\TenantSwitcher;

class UseTenantByDomain
{
    public function handle(Request $request, Closure $next)
    {
        if (app()->runningInConsole()) {
            return $next($request);
        }

        Log::info('TENANT_ENTER', [
            'host'    => $request->getHost(),
            'before_default' => config('database.default'),
            'before_db'      => \DB::connection()->getDatabaseName(),
        ]);

        app(TenantSwitcher::class)->switchByDomain($request->getHost());

        Log::info('TENANT_EXIT', [
            'after_default'  => config('database.default'),
            'after_db'       => \DB::connection()->getDatabaseName(),
            'tenant_user'    => config('database.connections.tenant.username'),
        ]);

        return $next($request);
    }
}
