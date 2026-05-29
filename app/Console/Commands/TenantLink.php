<?php

// app/Console/Commands/TenantLink.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TenantLink extends Command
{
    protected $signature = 'tenant:link {domain} {db_name} {--host=127.0.0.1}';
    protected $description = 'Map a domain to an existing MySQL database';

    public function handle()
    {
        $domain = $this->argument('domain');
        $db     = $this->argument('db_name');
        $host   = $this->option('host');

        DB::table('tenants')->updateOrInsert(
            ['domain' => $domain],
            ['db_name' => $db, 'db_host' => $host, 'updated_at' => now(), 'created_at' => now()]
        );

        cache()->forget("tenant:$domain");

        $this->info("Linked {$domain} -> {$host}/{$db}");
    }
}
