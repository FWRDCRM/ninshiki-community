<?php

namespace MarJose123\Ninshiki\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class ProfileController
{
    public function index()
    {
        $resp = Http::pool(fn (Pool $pool) => [
            $pool->as('devices')
                ->ninshiki()
                ->withToken(\request()->session()->get('token'))
                ->get(config('ninshiki.api_version').'/sessions'),
            $pool->as('permissions')
                ->ninshiki()
                ->withToken(\request()->session()->get('token'))
                ->get(config('ninshiki.api_version').'/profile/permissions'),
        ]);

        return Inertia::render('profile/index', [
            'logout_other_devices' => [
                'required_password' => in_array('access panel', $resp['permissions']->json()),
            ],
            'devices' => $resp['devices']->json(),
        ]);
    }

    /**
     * Logout other devices
     *
     * @throws ConnectionException
     */
    public function logoutOtherDevices()
    {
        $resp = Http::ninshiki()
            ->withToken(\request()->session()->get('token'))
            ->post(config('ninshiki.api_version').'/sessions/logout/devices');

        return to_route('profile');
    }
}
