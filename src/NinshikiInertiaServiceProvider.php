<?php

namespace MarJose123\Ninshiki;

use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\ServiceProvider;

class NinshikiInertiaServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(Middleware $middleware): void
    {
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);
    }
}
