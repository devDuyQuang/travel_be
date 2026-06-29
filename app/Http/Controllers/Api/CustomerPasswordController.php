<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CustomerPasswordResetRequest;
use App\Models\User;
use App\Services\CustomerAccountService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CustomerPasswordController extends Controller
{
    public function reset(CustomerPasswordResetRequest $request, CustomerAccountService $customers): JsonResponse
    {
        $data = $request->validated();
        $user = User::query()
            ->with('role:id,code')
            ->where('email', $data['email'])
            ->first();

        if (! $user || ! $customers->isCustomerAccount($user)) {
            throw ValidationException::withMessages([
                'email' => 'Link thiết lập mật khẩu không hợp lệ hoặc không dành cho tài khoản này.',
            ]);
        }

        $status = Password::broker()->reset(
            $data,
            function (User $user, string $password) use ($customers): void {
                $user->loadMissing('role:id,code');

                if (! $customers->isCustomerAccount($user)) {
                    throw ValidationException::withMessages([
                        'email' => 'Link thiết lập mật khẩu không hợp lệ hoặc không dành cho tài khoản này.',
                    ]);
                }

                $customers->promoteLegacyCustomer($user);

                $user->forceFill([
                    'password' => Hash::make($password),
                    'email_verified_at' => $user->email_verified_at ?: now(),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => $this->messageForStatus($status),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Mật khẩu đã được thiết lập. Tài khoản của bạn đã sẵn sàng.',
        ]);
    }

    private function messageForStatus(string $status): string
    {
        return match ($status) {
            Password::INVALID_TOKEN => 'Link thiết lập mật khẩu không hợp lệ hoặc đã được sử dụng.',
            Password::INVALID_USER => 'Không tìm thấy tài khoản phù hợp với email này.',
            default => 'Không thể thiết lập mật khẩu. Vui lòng kiểm tra lại link hoặc thử lại sau.',
        };
    }
}
