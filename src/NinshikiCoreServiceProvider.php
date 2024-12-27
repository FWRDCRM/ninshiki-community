<?php

namespace MarJose123\Ninshiki;

use Illuminate\Support\ServiceProvider;

class NinshikiCoreServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void {}

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
