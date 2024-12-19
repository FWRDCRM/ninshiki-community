<?php

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use MarJose123\Ninshiki\Http\Middleware\HandleInertiaRequestsMiddleware;

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
    'name' => env('NINSHIKI_NAME', env('APP_NAME')),

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
        HandleInertiaRequestsMiddleware::class,
        StartSession::class,
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        ShareErrorsFromSession::class,
        VerifyCsrfToken::class,
    ],

];
