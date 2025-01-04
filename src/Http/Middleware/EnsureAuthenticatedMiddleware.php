<?php

namespace MarJose123\Ninshiki\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class EnsureAuthenticatedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $resp = Http::ninshiki()
            ->retry(5)
            ->withToken($request->session()->get('token'))
            ->get(config('ninshiki.api_version').'/sessions/health');
        if (! $resp->ok()) {
            $request->session()->flush();
            $request->session()->regenerate();

            if ($request->wantsJson()) {
                return response()->json([
                    'error' => [
                        'code' => 401,
                        'message' => 'Unauthenticated.',
                        'success' => false,
                    ],
                ], 401);
            }

            return Inertia::render('error/index', [
                'status' => 401,
                'redirect' => route('login.page'),
            ])->toResponse($request)
                ->setStatusCode(401);
        }

        return $next($request);
    }
}
