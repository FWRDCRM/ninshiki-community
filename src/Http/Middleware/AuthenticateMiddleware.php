<?php

namespace MarJose123\Ninshiki\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticateMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->session()->has('token') && $request->session()->has('user')) {
            return $next($request);
        }

        return redirect(route('login.page'));
    }
}
