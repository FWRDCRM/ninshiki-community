<?php

namespace MarJose123\Ninshiki\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class FeedsController
{
    /**
     * @throws ConnectionException
     */
    public function index(Request $request)
    {
        $response = Http::ninshiki()
            ->withToken($request->session()->get('token'))
            ->get(config('ninshiki.api_version').'/posts');
        dd($response);

        return Inertia::render('feed/index', [
            'posts' => [],
        ]);
    }
}
