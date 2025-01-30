<?php

namespace MarJose123\Ninshiki;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Http\Client\PendingRequest;
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
        $this->registerAboutCommand();
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

        PendingRequest::macro('ninshiki', function () {
            $this->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => Request::header('User-Agent'),
            ]);
            $this->baseUrl(config('ninshiki.backend').'/api');

            return $this;
        });

        Http::macro('giphy', function () {
            return Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
                ->baseUrl('https://api.giphy.com/v1');
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
                'websocket' => [
                    'enabled' => config('ninshiki.websocket.enabled', true),
                    'key' => config('ninshiki.websocket.key'),
                    'host' => config('ninshiki.websocket.host'),
                    'port' => config('ninshiki.websocket.port'),
                    'scheme' => config('ninshiki.websocket.scheme'),
                ],
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

    protected function registerAboutCommand(): void
    {
        $formatEnabledStatus = fn ($value) => $value ? '<fg=yellow;options=bold>ENABLED</>' : 'OFF';

        AboutCommand::add('Ninshki UI', function (AboutCommand $command) use ($formatEnabledStatus) {
            return [
                'Giphy Integration' => AboutCommand::format(is_null(config('ninshiki.giphy.token')), console: $formatEnabledStatus),
                'Backend URL' => config('ninshiki.backend'),
                'Backend API Version' => config('ninshiki.api_version'),
                'URL' => config('ninshiki.path'),
                'Ninshiki UI Version' => Ninshiki::version(),
            ];
        });
    }
}
