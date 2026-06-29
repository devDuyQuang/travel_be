<?php

namespace Tests\Feature;

use Tests\TestCase;

class SanctumSpaFoundationTest extends TestCase
{
    public function test_sanctum_csrf_cookie_endpoint_is_available(): void
    {
        $this->withHeader('Origin', 'http://localhost:3000')
            ->withHeader('Accept', 'application/json')
            ->get('http://api.example.test/sanctum/csrf-cookie')
            ->assertNoContent();
    }

    public function test_cors_credentials_do_not_use_wildcard_origin(): void
    {
        $this->assertTrue(config('cors.supports_credentials'));
        $this->assertNotContains('*', config('cors.allowed_origins'));
        $this->assertContains('http://localhost:3000', config('cors.allowed_origins'));
    }

    public function test_sanctum_uses_web_guard_for_spa_sessions(): void
    {
        $this->assertContains('web', config('sanctum.guard'));
        $this->assertContains('customer', config('sanctum.guard'));
        $this->assertContains('localhost:3000', config('sanctum.stateful'));
    }
}
