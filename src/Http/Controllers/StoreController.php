<?php

namespace MarJose123\Ninshiki\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class StoreController
{
    /**
     * @throws ConnectionException
     */
    public function index(Request $request)
    {
        $response = Http::ninshiki()
            ->withToken($request->session()->get('token'))
            ->get(config('ninshiki.api_version').'/shop');

        return Inertia::render('store/index', [
            'products' => $response->json('data'),
        ]);
    }
}
