<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Hiển thị form đăng nhập chung
    public function showLoginForm()
    {
        return view('login');
    }

    // Xử lý đăng nhập chung cho cả Admin & Customer
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Kiểm tra vai trò tài khoản để chuyển hướng đúng vị trí
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin'); // Admin nhảy vào trang Quản trị
            }

            return redirect()->route('shop'); // Customer nhảy vào trang Mua sắm
        }

        return back()->withErrors(['email' => 'Email hoặc mật khẩu không chính xác.']);
    }

    // Xử lý Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}