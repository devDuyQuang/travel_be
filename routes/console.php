<?php

// use Illuminate\Foundation\Inspiring;
// use Illuminate\Support\Facades\Artisan;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Artisan;
use App\Services\TenantSwitcher;

Artisan::command('tenant:migrate {domain}', function (string $domain) {
    // 1) Switch connection sang tenant theo domain
    app(TenantSwitcher::class)->switchByDomain($domain);

    // 2) Chạy migrate trên connection 'tenant'
    $this->call('migrate', ['--database' => 'tenant']);
})->purpose('Run migrations for a specific tenant by domain');

Artisan::command('tenant:rollback {domain} {--step=1}', function (string $domain) {
    app(TenantSwitcher::class)->switchByDomain($domain);
    $this->call('migrate:rollback', [
        '--database' => 'tenant',
        '--step' => $this->option('step'),
    ]);
})->purpose('Rollback tenant migrations');

Artisan::command('tenant:seed {domain} {--class=DatabaseSeeder}', function (string $domain) {
    // Switch tenant theo domain
    app(TenantSwitcher::class)->switchByDomain($domain);

    // Chạy seed cho connection 'tenant'
    $this->call('db:seed', [
        '--database' => 'tenant',
        '--class'    => $this->option('class'),
    ]);
})->purpose('Seed a specific tenant DB by domain');