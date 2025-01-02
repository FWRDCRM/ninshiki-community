<?php

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Inertia\EncryptHistoryMiddleware;
use MarJose123\Ninshiki\Http\Middleware\AuthenticateMiddleware;
use MarJose123\Ninshiki\Http\Middleware\DispatchServingNinshikiEvent;
use MarJose123\Ninshiki\Http\Middleware\HandleInertiaRequestsMiddleware;
use MarJose123\Ninshiki\Http\Middleware\RedirectIfAuthenticated;

return [

    /**
     * ----------------------------------------------------------------------------------------------
     * Ninshiki App Name
     * ----------------------------------------------------------------------------------------------
     *
     * This value is the name of your application. This value is used when the
     * framework needs to display the name of the application within the UI
     * or in other locations. Of course, you're free to change the value.
     */
    'name' => env('NINSHIKI_NAME', 'Ninshiki'),

    /**
     * ----------------------------------------------------------------------------------------------
     * Ninshiki Backend Application URL
     * ----------------------------------------------------------------------------------------------
     *
     * This value is the "APP_URL" associated to the Ninshiki Backend Application. This will be used
     * to connect to the Backend API of the Ninshiki.
     *
     * Ninshiki Backend API Application: https://github.com/ninshiki-project/Ninshiki-backend
     */
    'backend' => env('NINSHIKI_BACKEND', env('APP_URL')),

    /**
     * ----------------------------------------------------------------------------------------------
     * Ninshiki Backend API version
     * ----------------------------------------------------------------------------------------------
     * This value should be something in pretty format versioning like "v1" format
     */
    'api_version' => env('NINSHIKI_API_VERSION', 'v1'),

    /**
     * ----------------------------------------------------------------------------------------------
     *  GIPHY Integration
     * ----------------------------------------------------------------------------------------------
     * This will be used for the GIPHY integration for GIF feature in creating a post
     *
     * Create Giphy Developer account and generate token https://developers.giphy.com
     */
    'giphy' => [
        'token' => env('NINSHIKI_GIPHY_TOKEN', null),
        'result' => [
            'limit' => env('NINSHIKI_GIPHY_LIMIT_RESULT', 30),
        ],
    ],

    /**
     * ----------------------------------------------------------------------------------------------
     * Ninshiki Domain Name
     * ----------------------------------------------------------------------------------------------
     *
     * This value is the "domain name" associated with your application. This
     * can be used to prevent Ninshiki's internal routes from being registered
     * on subdomains which do not need access to your admin application.
     */
    'domain' => env('Ninshiki_DOMAIN_NAME', null),

    /**
     * ----------------------------------------------------------------------------------------------
     * Ninshiki Path
     * ----------------------------------------------------------------------------------------------
     *
     * This is the URI path where Ninshiki will be accessible from. Feel free to
     * change this path to anything you like.
     */
    'path' => env('NINSHIKI_PATH', '/'),

    /**
     * ----------------------------------------------------------------------------------------------
     * Ninshiki Route Middleware
     * ----------------------------------------------------------------------------------------------
     *
     * These middleware will be assigned to every Ninshiki route, giving you the
     * chance to add your own middleware to this stack or override any of
     * the existing middleware. Or, you can just stick with this stack.
     *
     * Note: Please don't add laravel authentication middleware since Ninshiki has its
     * own authentication middleware
     */
    'middleware' => [
        StartSession::class,
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        ShareErrorsFromSession::class,
        VerifyCsrfToken::class,
        HandleInertiaRequestsMiddleware::class,
        DispatchServingNinshikiEvent::class,
    ],

    'authMiddleware' => [
        //        EncryptHistoryMiddleware::class,
        AuthenticateMiddleware::class,
    ],

    'guestMiddleware' => [
        RedirectIfAuthenticated::class,
    ],

];
