<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLogin
{
    /**
     * Block every page that needs an account.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->get('AccountID')) {
            return $next($request);
        }

        return redirect()->route('login')->with('error', 'Đăng nhập hoặc đăng ký để xem danh sách phòng');
    }
}
