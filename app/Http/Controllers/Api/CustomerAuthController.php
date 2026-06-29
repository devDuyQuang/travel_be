<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    private const INVALID_LOGIN_MESSAGE = 'Email hoặc mật khẩu không đúng.';

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email = mb_strtolower(trim($validated['email']));
        $password = $validated['password'];
        $user = User::query()
            ->with('role')
            ->whereRaw('LOWER(email) = ?', [$email])
            ->first();

        if (! $user || $user->role?->code !== 'customer' || ! Hash::check($password, $user->password)) {
            return $this->invalidLoginResponse();
        }

        Auth::guard('customer')->login($user);
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'data' => $this->customerPayload($user),
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user('customer')?->loadMissing('role');

        if (! $user || $user->role?->code !== 'customer') {
            abort(403);
        }

        return response()->json([
            'success' => true,
            'data' => $this->customerPayload($user),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::guard('customer')->logout();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công.',
        ]);
    }

    private function customerPayload(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role?->code,
        ];
    }

    private function invalidLoginResponse(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => self::INVALID_LOGIN_MESSAGE,
            'errors' => [
                'email' => [self::INVALID_LOGIN_MESSAGE],
            ],
        ], 422);
    }
}
