<?php

namespace MarJose123\Ninshiki\Http\Middleware;

use Closure;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class UserProfileMiddleware
{
    /**
     * @throws ConnectionException
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->session()->has('token') && $request->session()->has('user')) {

            $response = Http::ninshiki()
                ->withToken($request->session()->get('token'))
                ->get(config('ninshiki.api_version').'/auth/me');

            Inertia::share('auth.user', $response->json('data'));
        }

        return $next($request);
    }
}
