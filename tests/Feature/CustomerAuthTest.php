<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_login_with_valid_credentials(): void
    {
        $customer = $this->userWithRole('customer', [
            'email' => 'customer@example.test',
            'password' => Hash::make('customer-password'),
        ]);

        $this->postJson('http://api.example.test/customer/login', [
            'email' => ' CUSTOMER@example.test ',
            'password' => 'customer-password',
        ])->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $customer->id)
            ->assertJsonPath('data.email', 'customer@example.test')
            ->assertJsonPath('data.role', 'customer')
            ->assertJsonMissingPath('data.password')
            ->assertJsonMissingPath('data.remember_token')
            ->assertJsonMissingPath('data.permissions');

        $this->assertAuthenticatedAs($customer, 'customer');
    }

    public function test_wrong_password_receives_generic_error(): void
    {
        $this->userWithRole('customer', [
            'email' => 'customer@example.test',
            'password' => Hash::make('customer-password'),
        ]);

        $this->postJson('http://api.example.test/customer/login', [
            'email' => 'customer@example.test',
            'password' => 'wrong-password',
        ])->assertUnprocessable()
            ->assertJsonPath('message', 'Email hoặc mật khẩu không đúng.');
    }

    public function test_missing_email_receives_generic_error(): void
    {
        $this->postJson('http://api.example.test/customer/login', [
            'email' => 'missing@example.test',
            'password' => 'customer-password',
        ])->assertUnprocessable()
            ->assertJsonPath('message', 'Email hoặc mật khẩu không đúng.');
    }

    public function test_admin_super_admin_and_user_roles_cannot_login_as_customer(): void
    {
        foreach (['admin', 'super-admin', 'user'] as $roleCode) {
            $user = $this->userWithRole($roleCode, [
                'email' => "{$roleCode}@example.test",
                'password' => Hash::make('shared-password'),
            ]);

            $this->postJson('http://api.example.test/customer/login', [
                'email' => $user->email,
                'password' => 'shared-password',
            ])->assertUnprocessable()
                ->assertJsonPath('message', 'Email hoặc mật khẩu không đúng.');

            $this->assertGuest('customer');
        }
    }

    public function test_customer_me_returns_minimal_customer_payload(): void
    {
        $customer = $this->userWithRole('customer');

        $this->actingAs($customer, 'customer')
            ->getJson('http://api.example.test/customer/me')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $customer->id)
            ->assertJsonPath('data.email', $customer->email)
            ->assertJsonPath('data.role', 'customer')
            ->assertJsonMissingPath('data.password')
            ->assertJsonMissingPath('data.remember_token')
            ->assertJsonMissingPath('data.permissions');
    }

    public function test_guest_customer_me_receives_unauthenticated(): void
    {
        $this->getJson('http://api.example.test/customer/me')
            ->assertUnauthorized();
    }

    public function test_customer_logout_clears_customer_guard(): void
    {
        $customer = $this->userWithRole('customer');

        $this->actingAs($customer, 'customer')
            ->postJson('http://api.example.test/customer/logout')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertGuest('customer');

        $this->getJson('http://api.example.test/customer/me')
            ->assertUnauthorized();
    }

    public function test_customer_cannot_access_admin_but_admin_can(): void
    {
        $customer = $this->userWithRole('customer');
        $admin = $this->userWithRole('admin');

        $this->actingAs($customer)
            ->get('http://cms.example.test/booking')
            ->assertForbidden();

        $this->actingAs($admin)
            ->get('http://cms.example.test/booking')
            ->assertOk();
    }

    public function test_customer_logout_does_not_logout_existing_admin_session(): void
    {
        $admin = $this->userWithRole('admin');
        $customer = $this->userWithRole('customer', [
            'email' => 'customer@example.test',
            'password' => Hash::make('customer-password'),
        ]);

        $this->actingAs($admin, 'web');

        $this->postJson('http://api.example.test/customer/login', [
            'email' => $customer->email,
            'password' => 'customer-password',
        ])->assertOk();

        $this->assertAuthenticatedAs($admin, 'web');
        $this->assertAuthenticatedAs($customer, 'customer');

        $this->postJson('http://api.example.test/customer/logout')
            ->assertOk();

        $this->assertAuthenticatedAs($admin, 'web');
        $this->assertGuest('customer');
    }

    public function test_admin_login_does_not_clear_existing_customer_guard(): void
    {
        $customer = $this->userWithRole('customer');
        $admin = $this->userWithRole('admin', [
            'email' => 'admin@example.test',
            'password' => Hash::make('admin-password'),
        ]);

        $this->actingAs($customer, 'customer');
        Auth::shouldUse('web');

        $this->post('http://cms.example.test/login', [
            'email' => $admin->email,
            'password' => 'admin-password',
        ])->assertRedirect();

        $this->assertAuthenticatedAs($admin, 'web');
        $this->assertAuthenticatedAs($customer, 'customer');

        $this->getJson('http://api.example.test/customer/me')
            ->assertOk()
            ->assertJsonPath('data.id', $customer->id);
    }

    public function test_public_apis_remain_public(): void
    {
        $this->getJson('http://api.example.test/product')
            ->assertOk();

        $this->getJson('http://api.example.test/category')
            ->assertOk();
    }

    private function userWithRole(string $roleCode, array $attributes = []): User
    {
        $role = Role::query()->firstOrCreate(
            ['code' => $roleCode],
            ['name' => ucfirst($roleCode), 'status' => 1]
        );

        return User::factory()->create(array_merge([
            'role_id' => $role->id,
            'password' => Hash::make('password'),
        ], $attributes));
    }
}
