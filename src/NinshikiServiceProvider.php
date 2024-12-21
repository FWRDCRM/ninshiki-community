<?php

namespace MarJose123\Ninshiki;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use MarJose123\Ninshiki\Console\Commands\PublishCommand;
use Psr\Http\Message\RequestInterface;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class NinshikiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('ninshiki')
            ->hasConfigFile()
            ->hasViews()
            ->hasInertiaComponents()
            ->hasAssets()
            ->hasConsoleCommand(PublishCommand::class)
            ->hasRoute('web')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->startWith(function (InstallCommand $command) {
                        $command->info('Installing Ninshiki...');
                    })
                    ->publishConfigFile()
                    ->publishAssets()
                    ->askToStarRepoOnGitHub('ninshiki-project/ninshiki')
                    ->endWith(function (InstallCommand $command) {
                        $command->info('Have a great day!');
                    });

            });
    }

    public function packageBooted(): void
    {
        /**
         * --------------------------------------------------------------------------------
         *  HTTP MACRO
         * --------------------------------------------------------------------------------
         */
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
}
