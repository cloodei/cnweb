<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {

        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); 
        }

        abort(403, 'Khu vực cấm! Bạn không có quyền Quản trị viên để truy cập trang này.');
    }
}