<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class CustomerAccountService
{
    public function resolveForBooking(string $email, string $name): array
    {
        $email = Str::lower(trim($email));
        $customerRole = $this->customerRole();

        $user = User::query()
            ->where('email', $email)
            ->lockForUpdate()
            ->first();

        if ($user) {
            $this->promoteLegacyCustomer($user, $customerRole);

            return [
                'user' => $user,
                'created' => false,
                'setup_url' => $user->email_verified_at ? null : $this->passwordSetupUrl($user),
            ];
        }

        $user = User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make(Str::random(64)),
            'role_id' => $customerRole->id,
        ]);

        return [
            'user' => $user,
            'created' => true,
            'setup_url' => $this->passwordSetupUrl($user),
        ];
    }

    public function passwordSetupUrl(User $user): string
    {
        $baseUrl = rtrim((string) config('services.frontend.url', config('app.url')), '/');
        $query = http_build_query([
            'token' => Password::broker()->createToken($user),
            'email' => $user->email,
        ]);

        return "{$baseUrl}/reset-password?{$query}";
    }

    public function customerRole(): Role
    {
        return Role::query()->firstOrCreate(
            ['code' => 'customer'],
            [
                'name' => 'Customer',
                'description' => 'Khách hàng đặt dịch vụ',
                'status' => 1,
            ]
        );
    }

    public function isCustomerAccount(User $user): bool
    {
        return in_array($user->role?->code, ['customer', 'user'], true);
    }

    public function promoteLegacyCustomer(User $user, ?Role $customerRole = null): void
    {
        if ($user->role?->code !== 'user') {
            return;
        }

        $user->forceFill([
            'role_id' => ($customerRole ?: $this->customerRole())->id,
        ])->save();

        $user->setRelation('role', $customerRole ?: $user->role()->first());
    }
}
