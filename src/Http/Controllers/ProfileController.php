<?php

namespace MarJose123\Ninshiki\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
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
    public function logoutOtherDevices(Request $request)
    {
        $resp = Http::ninshiki()
            ->withToken(\request()->session()->get('token'))
            ->post(config('ninshiki.api_version').'/sessions/logout/devices', [
                ...($request->has('password') ? ['password' => $request->password] : []),
            ]);

        if ($resp->status() === 422) {
            $error = $resp->json();
            throw ValidationException::withMessages($error['error']['errors']);
        }

        return to_route('profile');
    }
}
