<?php

namespace MarJose123\Ninshiki;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Message\RequestInterface;

class NinshikiCoreServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->registerHttpMacros();
        $this->registerJsonVariables();
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

    protected function registerJsonVariables()
    {
        Ninshiki::serving(function () {
            Ninshiki::config([
                'appName' => Ninshiki::name() ?? config('app.name', 'Ninshiki'), /** @phpstan-ignore nullCoalesce.expr */
                'timezone' => config('app.timezone', 'UTC'),
                'locale' => config('app.locale', 'en'),
                'version' => Ninshiki::version(),
            ]);
        });
    }
}
