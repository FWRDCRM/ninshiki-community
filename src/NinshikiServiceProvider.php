<?php

namespace MarJose123\Ninshiki;

use Illuminate\Routing\Route;
use MarJose123\Ninshiki\Http\Middleware\HandleInertiaRequestsMiddleware;
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
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->startWith(function (InstallCommand $command) {
                        $command->info('Installing Ninshiki...');
                    })
                    ->publishConfigFile()
                    ->publishConfigFile()
                    ->copyAndRegisterServiceProviderInApp()
                    ->askToStarRepoOnGitHub('ninshiki-project/ninshiki')
                    ->endWith(function (InstallCommand $command) {
                        $command->info('Have a great day!');
                    });

            });
    }

    public function bootingPackage(): void
    {
        $router = $this->app->make(Route::class);
        $router->middleware('inertia', HandleInertiaRequestsMiddleware::class);
    }
}
