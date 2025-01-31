<?php

namespace MarJose123\Ninshiki\Http\Middleware;

use Closure;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use MarJose123\Ninshiki\Ninshiki;

class HandleServerMaintenanceMiddleware
{
    /**
     * @throws ConnectionException
     */
    public function handle(Request $request, Closure $next)
    {

        $response = Http::baseUrl(config('ninshiki.backend'))->get('');
        if ($response->status() === 503) {
            Inertia::setRootView(Ninshiki::$rootViewApp);

            return Inertia::render('error/index', [
                'status' => $response->status(),
            ]);
        }

        return $next($request);
    }
}
