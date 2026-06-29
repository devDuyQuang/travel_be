<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class CustomerPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    private int $requestCounter = 1;

    public function test_customer_can_reset_password_with_valid_token(): void
    {
        $customer = $this->customerUser();
        $token = Password::broker()->createToken($customer);

        $this->postReset([
            'token' => $token,
            'email' => $customer->email,
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ])->assertOk()
            ->assertJsonPath('success', true);

        $customer->refresh();
        $this->assertTrue(Hash::check('new-password-123', $customer->password));
        $this->assertNotNull($customer->email_verified_at);
    }

    public function test_invalid_token_is_rejected(): void
    {
        $customer = $this->customerUser();

        $this->postReset([
            'token' => 'not-a-valid-token',
            'email' => $customer->email,
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ])->assertUnprocessable()
            ->assertJsonPath('success', false);

        $this->assertTrue(Hash::check('old-password-123', $customer->fresh()->password));
    }

    public function test_expired_token_is_rejected(): void
    {
        $customer = $this->customerUser();
        $token = Password::broker()->createToken($customer);

        DB::table('password_reset_tokens')
            ->where('email', $customer->email)
            ->update(['created_at' => now()->subMinutes(config('auth.passwords.users.expire') + 1)]);

        $this->postReset([
            'token' => $token,
            'email' => $customer->email,
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ])->assertUnprocessable()
            ->assertJsonPath('success', false);

        $this->assertTrue(Hash::check('old-password-123', $customer->fresh()->password));
    }

    public function test_password_confirmation_must_match(): void
    {
        $customer = $this->customerUser();
        $token = Password::broker()->createToken($customer);

        $this->postReset([
            'token' => $token,
            'email' => $customer->email,
            'password' => 'new-password-123',
            'password_confirmation' => 'different-password',
        ])->assertUnprocessable()
            ->assertJsonPath('success', false);

        $this->assertTrue(Hash::check('old-password-123', $customer->fresh()->password));
    }

    public function test_token_cannot_be_reused(): void
    {
        $customer = $this->customerUser();
        $token = Password::broker()->createToken($customer);

        $payload = [
            'token' => $token,
            'email' => $customer->email,
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ];

        $this->postReset($payload)->assertOk();
        $this->postReset($payload)->assertUnprocessable()
            ->assertJsonPath('success', false);
    }

    public function test_legacy_user_role_can_setup_customer_password_and_is_promoted(): void
    {
        $legacyCustomer = $this->legacyUser();
        $token = Password::broker()->createToken($legacyCustomer);

        $this->postReset([
            'token' => $token,
            'email' => $legacyCustomer->email,
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ])->assertOk()
            ->assertJsonPath('success', true);

        $legacyCustomer->refresh()->load('role');
        $this->assertSame('customer', $legacyCustomer->role?->code);
        $this->assertTrue(Hash::check('new-password-123', $legacyCustomer->password));
        $this->assertNotNull($legacyCustomer->email_verified_at);
    }

    public function test_admin_account_cannot_use_customer_reset_flow(): void
    {
        $admin = $this->adminUser();
        $oldPassword = $admin->password;
        $token = Password::broker()->createToken($admin);

        $this->postReset([
            'token' => $token,
            'email' => $admin->email,
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ])->assertUnprocessable()
            ->assertJsonPath('success', false);

        $this->assertSame($oldPassword, $admin->fresh()->password);
    }

    private function postReset(array $payload)
    {
        return $this->withServerVariables([
            'REMOTE_ADDR' => '10.10.0.'.$this->requestCounter++,
        ])->postJson('http://api.example.test/customer/password/reset', $payload);
    }

    private function customerUser(): User
    {
        $role = Role::query()->firstOrCreate(
            ['code' => 'customer'],
            ['name' => 'Customer', 'status' => 1]
        );

        return User::factory()->create([
            'email_verified_at' => null,
            'password' => Hash::make('old-password-123'),
            'role_id' => $role->id,
        ]);
    }

    private function adminUser(): User
    {
        $role = Role::query()->firstOrCreate(
            ['code' => 'admin'],
            ['name' => 'Admin', 'status' => 1]
        );

        return User::factory()->create([
            'password' => Hash::make('admin-password-123'),
            'role_id' => $role->id,
        ]);
    }

    private function legacyUser(): User
    {
        $role = Role::query()->firstOrCreate(
            ['code' => 'user'],
            ['name' => 'User', 'status' => 1]
        );

        return User::factory()->create([
            'email_verified_at' => null,
            'password' => Hash::make('old-password-123'),
            'role_id' => $role->id,
        ]);
    }
}
