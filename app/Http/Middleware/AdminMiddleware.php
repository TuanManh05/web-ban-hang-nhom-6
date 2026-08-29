<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/admin/login')->withErrors(['email' => 'Bạn cần đăng nhập tài khoản Admin.']);
        }

        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            return redirect('/admin/login')->withErrors(['email' => 'Tài khoản của bạn không phải Admin!']);
        }

        return $next($request);
    }
}