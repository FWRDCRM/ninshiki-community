<?php

namespace MarJose123\Ninshiki\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class NotificationController
{
    /**
     * @throws ConnectionException
     */
    public function index()
    {
        $response = Http::ninshiki()
            ->withToken(\request()->session()->get('token'))
            ->get(config('ninshiki.api_version').'/notifications', [
                'unread' => 1,
            ]);

        return Inertia::render('notification/index', [
            'notifications' => $response->json('data'),
        ]);

    }
}
