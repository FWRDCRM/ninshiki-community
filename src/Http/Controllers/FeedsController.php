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
        $posts = $response->json();
        $posts = [
            'data' => $posts['data'],
            'meta' => [
                'current_page' => $posts['meta']['current_page'],
                'last_page' => $posts['meta']['last_page'],
                'per_page' => $posts['meta']['per_page'],
                'total' => $posts['meta']['total'],
                'from' => $posts['meta']['from'],
                'to' => $posts['meta']['to'],
            ],
        ];

        return Inertia::render('feed/index', [
            'posts' => $posts,
        ]);
    }

    /**
     * @throws ConnectionException
     */
    public function likeUnlike(Request $request, $id)
    {
        $response = Http::ninshiki()
            ->withToken($request->session()->get('token'))
            ->patch(config('ninshiki.api_version').'/posts/'.$id.'/toggle/like');

        return $response->json();
    }
}
