<?php

namespace MarJose123\Ninshiki;

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
            ->hasRoute('web')
            ->publishesServiceProvider('NinshikiInertiaServiceProvider')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->startWith(function (InstallCommand $command) {
                        $command->info('Installing Ninshiki...');
                    })
                    ->publishConfigFile();
                $command->callSilent('inertia:middleware');
                $command
                    ->publishConfigFile()
                    ->copyAndRegisterServiceProviderInApp()
                    ->askToStarRepoOnGitHub('ninshiki-project/ninshiki')
                    ->endWith(function (InstallCommand $command) {
                        $command->info('Have a great day!');
                    });

            });
    }
}
