<?php

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use MarJose123\Ninshiki\Http\AuthenticationController;
use MarJose123\Ninshiki\Http\Middleware\HandleInertiaRequestsMiddleware;

Route::middleware([
    HandleInertiaRequestsMiddleware::class,
    StartSession::class,
    EncryptCookies::class,
    AddQueuedCookiesToResponse::class,
    ShareErrorsFromSession::class,
    VerifyCsrfToken::class,
])->group(function () {

    // Authentication Route
    Route::get('/', [AuthenticationController::class, 'index'])->name('login');


});
