<?php

namespace MarJose123\Ninshiki\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Inertia\Inertia;
use Inertia\Middleware;
use Inertia\ResponseFactory;
use Override;
use Symfony\Component\HttpFoundation\Response;

class HandleInertiaRequestsMiddleware extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'ninshiki::app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        if (config('app.asset_url')) {
            return md5(config('app.asset_url'));
        }

        if (file_exists($manifest = public_path('vendor/ninshiki/manifest.json'))) {
            return md5_file($manifest);
        }

        return null;
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => function () use ($request) {
                return [
                    'user' => $request->session()->has('user') ? $request->session()->get('user') : null,
                ];
            },
        ]);
    }

    /**
     * Handle the incoming request.
     *
     * @param  $request
     * @param  Closure  $next
     * @return Response
     */
    #[Override]
    public function handle($request, Closure $next): Response
    {
        Config::set('inertia.ssr.enabled', false);
        return parent::handle($request, $next);
    }
}
