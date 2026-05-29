<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenantSwitcher
{
    protected function normalizeDomain(string $domain): string
    {
        $d = \Illuminate\Support\Str::of($domain)->lower()->trim();
        if ($d->startsWith('www.')) $d = $d->substr(4);
        return (string) $d;
    }

    protected function sldSlug(string $domain): string
    {
        // Chuẩn hóa domain (bỏ giao thức, đường dẫn)
        $norm = $this->normalizeDomain($domain);
        $norm = preg_replace('#^https?://#', '', $norm);
        $norm = preg_replace('#/.*$#', '', $norm);

        $parts = explode('.', $norm);
        $count = count($parts);
        if ($count === 0) {
            return '';
        }

        // Danh sách TLD nhiều tầng phổ biến ở VN
        $multiTLDs = [
            'com.vn',
            'net.vn',
            'org.vn',
            'gov.vn',
            'edu.vn',
            'biz.vn',
            'info.vn',
            'name.vn',
            'health.vn',
        ];

        // Lấy 2 phần cuối để kiểm tra
        $lastTwo = implode('.', array_slice($parts, -2));

        if (in_array($lastTwo, $multiTLDs)) {
            // Nếu .com.vn → lấy phần đứng ngay trước nó
            $labelIdx = $count - 3;
            $tld = $lastTwo;
        } else {
            // Nếu chỉ có .com, .vn, .net...
            $labelIdx = $count - 2;
            $tld = $parts[$count - 1] ?? '';
        }

        // Nếu index âm (domain quá ngắn)
        if ($labelIdx < 0) {
            $labelIdx = 0;
        }

        $label = $parts[$labelIdx] ?? '';

        // Ghép lại theo mẫu cũ
        $raw = $label;
        if ($tld !== '') {
            $raw = $label . '_' . str_replace('.', '_', $tld);
        }

        // Chuẩn hoá kết quả
        return \Illuminate\Support\Str::of($raw)
            ->replaceMatches('/[^a-z0-9]+/i', '_')
            ->trim('_')
            ->limit(32, '');
    }

    // public function switchByDomain(string $domain): void
    // {
    //     $name = $this->sldSlug($domain);

    //     $host = env('TENANT_DB_HOST', env('DB_HOST', '127.0.0.1'));
    //     $port = env('TENANT_DB_PORT', env('DB_PORT', 3306));
    //     $pass = env('TENANT_DB_PASSWORD', env('DB_PASSWORD'));

    //     \Config::set('database.connections.tenant.host', $host);
    //     \Config::set('database.connections.tenant.port', $port);
    //     \Config::set('database.connections.tenant.database', $name);
    //     \Config::set('database.connections.tenant.username', $name);
    //     \Config::set('database.connections.tenant.password', $pass);

    //     \DB::purge('tenant');
    //     \DB::reconnect('tenant');
    //     \Config::set('database.default', 'tenant');
    // }

    public function switchByDomain(string $domain): void
    {
        if (app()->environment('local') && str_ends_with($domain, 'localhost')) {
            $host = env('TENANT_DB_HOST', env('DB_HOST', '127.0.0.1'));
            $port = env('TENANT_DB_PORT', env('DB_PORT', 3306));
            $db   = env('TENANT_DB_DATABASE', env('DB_DATABASE', 'localhost'));
            $user = env('TENANT_DB_USERNAME', env('DB_USERNAME', 'root'));
            $pass = env('TENANT_DB_PASSWORD', env('DB_PASSWORD'));

            \Config::set('database.connections.tenant.host', $host);
            \Config::set('database.connections.tenant.port', $port);
            \Config::set('database.connections.tenant.database', $db);
            \Config::set('database.connections.tenant.username', $user);
            \Config::set('database.connections.tenant.password', $pass);

            \DB::purge('tenant');
            \DB::reconnect('tenant');
            \Config::set('database.default', 'tenant');

            return;
        }

        $name = $this->sldSlug($domain);

        $host = env('TENANT_DB_HOST', env('DB_HOST', '127.0.0.1'));
        $port = env('TENANT_DB_PORT', env('DB_PORT', 3306));
        $pass = env('TENANT_DB_PASSWORD', env('DB_PASSWORD'));

        \Config::set('database.connections.tenant.host', $host);
        \Config::set('database.connections.tenant.port', $port);
        \Config::set('database.connections.tenant.database', $name);
        \Config::set('database.connections.tenant.username', $name);
        \Config::set('database.connections.tenant.password', $pass);

        \DB::purge('tenant');
        \DB::reconnect('tenant');
        \Config::set('database.default', 'tenant');
    }
}
