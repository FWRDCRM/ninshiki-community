<?php

namespace MarJose123\Ninshiki;

use MarJose123\Ninshiki\Commands\NinshikiCommand;
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
            ->hasMigration('create_ninshiki_table')
            ->hasCommand(NinshikiCommand::class);
    }
}
