<?php

namespace MarJose123\Ninshiki\Http;

use Illuminate\Foundation\Application;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
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
}
