<?php

use MarJose123\Ninshiki\Http\AuthenticationController;

Route::middleware(config('ninshiki.middleware'))
    ->domain(config('ninshiki.domain'))
    ->group(function () {

        // Authentication Route
        Route::get('/', [AuthenticationController::class, 'index'])->name('login');
        Route::get('/provider/zoho', [AuthenticationController::class, 'requestForProviderLogin'])->name('login.requestProvider');
        Route::get('/callback/provider/zoho', [AuthenticationController::class, 'callbackForProviderLogin'])->name('login.callback');

    });
