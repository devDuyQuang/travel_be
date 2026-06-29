<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Hiển thị form login
    public function index()
    {
        if (Auth::check()) {
            // Nếu đã đăng nhập thì redirect sang dashboard
            return redirect()->to(panel_route('dashboard.index'));
        }

        // Nếu chưa login thì hiển thị form login
        return view('auth.login');
    }

    // Xử lý login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            if (! $this->canAccessAdminPanel(Auth::user())) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bạn không có quyền truy cập trang quản trị',
                    ], 403);
                }

                throw ValidationException::withMessages([
                    'email' => 'Bạn không có quyền truy cập trang quản trị',
                ]);
            }

            $request->session()->regenerate();

            // Nếu client mong JSON (Accept: application/json hoặc AJAX)
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đăng nhập thành công',
                    'redirect' => panel_route('dashboard.index'), // đổi theo app
                ]);
            }

            // Fallback cho form submit thường
            return redirect()->intended(panel_route('dashboard.index'));
        }

        // Sai thông tin
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Email hoặc mật khẩu không đúng',
            ], 422);
        }

        // Fallback non-AJAX
        throw ValidationException::withMessages([
            'email' => 'Email hoặc mật khẩu không đúng',
        ]);
    }

    private function canAccessAdminPanel(?User $user): bool
    {
        return $user && in_array($user->role?->code, ['admin', 'super-admin'], true);
    }

    // Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->to(panel_route('login'));
    }
}
