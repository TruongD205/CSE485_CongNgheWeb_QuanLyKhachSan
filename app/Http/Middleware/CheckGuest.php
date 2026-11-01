<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckGuest
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->has('AccountID')) {
            return redirect()->route('rooms.index');
            // ngăn cho ng dùng đã đăng nhập nhưng cố tình muốn quay lại trang đăng ký
        }

        return $next($request);
    }
}
