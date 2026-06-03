<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

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
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Nếu client mong JSON (Accept: application/json hoặc AJAX)
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Đăng nhập thành công',
                    'redirect' => panel_route('dashboard.index') // đổi theo app
                ]);
            }

            // Fallback cho form submit thường
            return redirect()->intended(panel_route('dashboard.index'));
        }

        // Sai thông tin
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Email hoặc mật khẩu không đúng'
            ], 422);
        }

        // Fallback non-AJAX
        throw ValidationException::withMessages([
            'email' => 'Email hoặc mật khẩu không đúng',
        ]);
    }

    // Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
