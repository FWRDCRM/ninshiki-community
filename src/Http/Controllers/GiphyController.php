<?php

namespace MarJose123\Ninshiki\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GiphyController
{
    public function trending(Request $request)
    {
        $reps = Http::giphy()
            ->withQueryParameters([
                'api_key' => '',
            ])->get('/gifs/trending');
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
