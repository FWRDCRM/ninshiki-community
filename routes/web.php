<?php

use MarJose123\Ninshiki\Http\Controllers\AuthenticationController;
use MarJose123\Ninshiki\Http\Controllers\FeedsController;

Route::middleware(config('ninshiki.middleware'))
    ->domain(config('ninshiki.domain'))
    ->group(function () {

        /**
         * ------------------------------------------------------------------------------
         *  UNAUTHENTICATED ROUTE
         * ------------------------------------------------------------------------------
         */
        Route::middleware(config('ninshiki.guestMiddleware'))
            ->group(function () {
                Route::get('/', [AuthenticationController::class, 'index'])->name('login');
                Route::get('/provider/zoho', [AuthenticationController::class, 'requestForProviderLogin'])->name('login.requestProvider');
                Route::get('/callback/provider/zoho', [AuthenticationController::class, 'callbackForProviderLogin'])->name('login.callback');
            });

        /**
         * ------------------------------------------------------------------------------
         *  AUTHENTICATED ROUTE
         * ------------------------------------------------------------------------------
         */
        Route::middleware(config('ninshiki.authMiddleware'))
            ->group(function () {
                Route::get('/feed', [FeedsController::class, 'index'])->name('feed');
            });
    });
