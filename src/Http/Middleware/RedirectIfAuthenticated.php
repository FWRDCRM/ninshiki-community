<?php

namespace MarJose123\Ninshiki\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->session()->has('token') && $request->session()->has('user')) {
            return redirect(route('feed'));
        }

        return $next($request);
    }
}
