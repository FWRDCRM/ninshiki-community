<?php

use MarJose123\Ninshiki\Http\AuthenticationController;

Route::middleware(config('ninshiki.middleware'))
    ->domain(config('ninshiki.domain'))
    ->group(function () {

        // Authentication Route
        Route::get('/', [AuthenticationController::class, 'index'])->name('login');

    });
