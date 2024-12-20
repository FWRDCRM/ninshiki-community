<?php

namespace MarJose123\Ninshiki;

use Illuminate\Support\Facades\Http;
use MarJose123\Ninshiki\Console\Commands\PublishCommand;
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

    public function packageBooted()
    {
        /**
         * --------------------------------------------------------------------------------
         *  HTTP MACRO
         * --------------------------------------------------------------------------------
         */
        Http::macro('ninshiki', function () {
            return Http::withHeaders([
                'Accept' => 'application/json',
            ])->baseUrl(config('ninshiki.backend').'/api');
        });

    }
}
