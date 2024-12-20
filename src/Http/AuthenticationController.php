<?php

namespace MarJose123\Ninshiki\Http;

use Illuminate\Foundation\Application;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class AuthenticationController
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * @throws ConnectionException
     */
    public function requestForProviderLogin(): Application|Redirector|RedirectResponse
    {
        $url = Http::ninshiki()->get('/api/login/zoho');

        return redirect($url);
    }

    /**
     * @throws ConnectionException
     */
    public function callbackForProviderLogin(Request $request): Redirector|RedirectResponse
    {
        $response = Http::ninshiki()
            ->post('/api/login/zoho', [
                'code' => $request->get('code'),
            ]);
        $body = $response->object();

        if ($response->status() === 401) {
            abort(401, $body->message);
        }

        if ($response->status() === 422) {
            abort(422, $body->message);
        }

        if (response()->status() === 200) {
            Inertia::render('Feeds', [
                'response' => $body,
            ]);
        }

    }
}
