<?php

namespace MarJose123\Ninshiki\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

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

        if (\request()->wantsJson()) {
            return response()->json($response->json());
        }
    }

    /**
     * @throws ConnectionException
     */
    public function markAsRead(string $id)
    {
        $response = Http::ninshiki()
            ->withToken(\request()->session()->get('token'))
            ->patch(config('ninshiki.api_version').'/notifications/'.$id.'/read');

        return back();
    }

    /**
     * @throws ConnectionException
     */
    public function markAllAsRead()
    {
        $response = Http::ninshiki()
            ->withToken(\request()->session()->get('token'))
            ->patch(config('ninshiki.api_version').'/notifications/read');

        return back();

    }
}
