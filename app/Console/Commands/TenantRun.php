<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;

class TenantRun extends Command
{
    protected $signature = 'tenant:run {cmd} {domain}';
    protected $description = 'Run any artisan command inside a tenant context';

    public function handle()
    {
        $cmd = $this->argument('cmd');
        $domain = $this->argument('domain');

        // DÙNG CENTRAL DB ĐỂ TÌM TENANT (NHƯ tenant:link)
        $tenant = DB::table('tenants')->where('domain', $domain)->first();

        if (!$tenant) {
            $this->error("Tenant not found: {$domain}");
            return 1;
        }

        $this->info("Found tenant: {$tenant->db_name} @ {$tenant->db_host}");

        // TẠO CONNECTION CHO TENANT
        Config::set('database.connections.tenant', [
            'driver'    => 'mysql',
            'host'      => $tenant->db_host,
            'port'      => 3306,
            'database'  => $tenant->db_name,
            'username'  => config('database.connections.mysql.username'),
            'password'  => config('database.connections.mysql.password'),
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => true,
        ]);

        Config::set('database.default', 'tenant');
        DB::purge('tenant');

        $this->info("Running: {$cmd}");

        $exitCode = Artisan::call($cmd);

        $this->info("Done (code: {$exitCode})");
        return $exitCode;
    }
}