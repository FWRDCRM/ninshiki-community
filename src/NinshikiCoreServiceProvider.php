<?php

namespace MarJose123\Ninshiki;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use MarJose123\Ninshiki\Events\ServingNinshiki;
use Psr\Http\Message\RequestInterface;

class NinshikiCoreServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->registerJsonVariables();
        $this->registerHttpMacros();
        $this->registerNinshikiAssets();
    }

    protected function registerHttpMacros(): void
    {
        Http::macro('ninshiki', function () {
            return Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => Request::header('User-Agent'),
            ])->baseUrl(config('ninshiki.backend').'/api');
        });

        /**
         * --------------------------------------------------------------------------------
         *  HTTP Client Global Middleware
         * --------------------------------------------------------------------------------
         */
        Http::globalRequestMiddleware(fn (RequestInterface $request) => $request->withHeader(
            'X-Start-At', now()->toDateTimeString()
        ));
        Http::globalResponseMiddleware(fn ($response) => $response->withHeader(
            'X-Finished-At', now()->toDateTimeString()
        ));
    }

    protected function registerJsonVariables(): void
    {
        Ninshiki::serving(function (ServingNinshiki $event) {
            Ninshiki::config([
                'appName' => Ninshiki::name() ?? config('app.name', 'Ninshiki'), /** @phpstan-ignore nullCoalesce.expr */
                'timezone' => config('app.timezone', 'UTC'),
                'locale' => config('app.locale', 'en'),
                'version' => Ninshiki::version(),
            ]);
        });
    }

    protected function registerNinshikiAssets(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../public/vendor/ninshiki' => public_path('vendor/ninshiki'),
            ], 'ninshiki-assets');
        }

    }
}
