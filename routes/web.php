<?php

use Inertia\Inertia;
use MarJose123\Ninshiki\Http\Controllers\AuthenticationController;
use MarJose123\Ninshiki\Http\Controllers\BroadcastController;
use MarJose123\Ninshiki\Http\Controllers\EmployeesController;
use MarJose123\Ninshiki\Http\Controllers\FeedsController;
use MarJose123\Ninshiki\Http\Controllers\GiftController;
use MarJose123\Ninshiki\Http\Controllers\GiphyController;
use MarJose123\Ninshiki\Http\Controllers\NotificationController;
use MarJose123\Ninshiki\Http\Controllers\ProfileController;
use MarJose123\Ninshiki\Http\Controllers\SessionController;
use MarJose123\Ninshiki\Http\Controllers\StoreController;
use MarJose123\Ninshiki\Http\Middleware\EnsureAuthenticatedMiddleware;
use MarJose123\Ninshiki\Ninshiki;

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
                Route::get('/', [AuthenticationController::class, 'index'])->name('login.page');
                Route::get('provider/zoho', [AuthenticationController::class, 'requestForProviderLogin'])->name('login.requestProvider');
                Route::get('callback/provider/zoho', [AuthenticationController::class, 'callbackForProviderLogin'])->name('login.callback');
            });

        /**
         * ------------------------------------------------------------------------------
         *  AUTHENTICATED ROUTE
         * ------------------------------------------------------------------------------
         */
        Route::middleware(array_merge(config('ninshiki.authMiddleware'), [EnsureAuthenticatedMiddleware::class]))
            ->group(function () {
                // logout
                Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout');
                // post
                Route::get('feed', [FeedsController::class, 'index'])->name('feed');
                Route::get('feed/{id}', [FeedsController::class, 'show'])->name('feed.show');
                Route::patch('feed/{id}', [FeedsController::class, 'likeUnlike'])->name('feeds.like-unlike');
                Route::post('feed', [FeedsController::class, 'createPost'])->name('feeds.create-post');
                // users
                Route::get('employees/list', [EmployeesController::class, 'index'])->name('employees.list');
                Route::get('employees', [EmployeesController::class, 'getAllEmployees'])->name('employees');
                // giphy
                Route::get('gif', [GiphyController::class, 'trending'])->name('gif.trending');
                Route::get('gif/search', [GiphyController::class, 'search'])->name('gif.search');

                // broadcast
                Route::post('broadcast/auth', BroadcastController::class)
                    ->name('broadcast.auth');

                // gift
                Route::post('gift/send', [GiftController::class, 'send'])->name('gift.send');

                // profile
                Route::get('profile', [ProfileController::class, 'index'])->name('profile');
                Route::post('profile/logout-other-devices', [ProfileController::class, 'logoutOtherDevices'])->name('profile.devices-other-logout');

                // Store
                Route::get('store', [StoreController::class, 'index'])->name('store.index');
                Route::post('store/redeem', [StoreController::class, 'redeem'])->name('store.redeem');
                Route::post('store/wishlist/toggle', [StoreController::class, 'toggleFavorite'])->name('store.wishlist.toggle');
                Route::delete('store/redeem/{id}/cancel', [StoreController::class, 'cancelRedeem'])->name('store.redeem.cancel');

                // Notification
                Route::get('notification', [NotificationController::class, 'index'])->name('notification.index');
                Route::patch('notification/{id}/read', [NotificationController::class, 'markAsRead'])->name('notification.mark-as-read');
                Route::patch('notification/read', [NotificationController::class, 'markAllAsRead'])->name('notification.mark-all-as-read');

                // session
                Route::get('session/health', [SessionController::class, 'heartbeat'])->name('session.heartbeat')
                    ->withoutMiddleware([
                        EnsureAuthenticatedMiddleware::class,

                    ]);

            });
    });

Route::fallback(function () {
    Inertia::setRootView(Ninshiki::$rootViewApp);

    return Inertia::render('error/index', [
        'status' => 404,
    ]);
});
