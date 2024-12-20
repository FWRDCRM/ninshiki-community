<?php

namespace MarJose123\Ninshiki\Http\Controllers;

use Illuminate\Foundation\Application;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticationController
{
    public function index(): Response
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * @throws ConnectionException
     */
    public function requestForProviderLogin(): Application|Redirector|RedirectResponse
    {
        $response = Http::ninshiki()->get('/login/zoho');

        if ($response->status() !== 200) {
            abort($response->status(), 'something went wrong');
        }

        return redirect($response->object()->link);
    }

    /**
     * @throws ConnectionException
     */
    public function callbackForProviderLogin(Request $request): RedirectResponse|Redirector|Response
    {
        $response = Http::ninshiki()
            ->post('/login/zoho', [
                'code' => $request->get('code'),
            ]);
        $body = $response->object();

        if ($response->status() === 401) {
            abort(401, $body->error->message);
        }

        if ($response->status() === 422) {
            abort(422, $body->error->message);
        }

        return Inertia::render('Feeds', [
            'response' => $body,
        ]);

    }
}
