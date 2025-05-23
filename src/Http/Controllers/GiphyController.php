<?php

namespace MarJose123\Ninshiki\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GiphyController
{
    /**
     * @throws ConnectionException
     */
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

            return response()->json($reps->json());
        }
    }

    /**
     * @throws ConnectionException
     */
    public function search(Request $request)
    {
        if ($request->wantsJson()) {
            $reps = Http::giphy()
                ->withQueryParameters([
                    'api_key' => config('ninshiki.giphy.token'),
                    'limit' => config('ninshiki.giphy.result.limit') ?? 30,
                    'rating' => 'g',
                    'offset' => $request->offset ?? 0,
                    'bundle' => 'messaging_non_clips',
                    'lang' => 'en',
                    'q' => $request->search,
                ])->get('/gifs/search');

            return response()->json($reps->json());
        }

    }
}
