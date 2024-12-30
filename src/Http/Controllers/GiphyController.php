<?php

namespace MarJose123\Ninshiki\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GiphyController
{
    public function trending(Request $request)
    {
        if ($request->wantsJson()) {
            $reps = Http::giphy()
                ->withQueryParameters([
                    'api_key' => config('ninshiki.giphy.token'),
                    'limit' => config('ninshiki.giphy.result.limit') ?? 30,
                    'rating' => 'g',
                    'bundle' => 'messaging_non_clips',
                ])->get('/gifs/trending');
        }
    }

    public function search(Request $request)
    {
        $reps = Http::giphy()
            ->withQueryParameters([
                'api_key' => '',
                'q' => $request->query('q'),
            ])->get('/gifs/search');
    }
}
