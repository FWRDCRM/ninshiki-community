<?php

namespace MarJose123\Ninshiki\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GiftController
{
    /**
     * @throws ConnectionException
     */
    public function send(Request $request)
    {
        $request->validate([
            'type' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
        ]);

        $response = Http::ninshiki()
            ->withToken($request->session()->get('token'))
            ->post(config('ninshiki.api_version').'/gifts/send', [
                'type' => $request->type,
                'amount' => $request->amount,
                'receiver' => $request->receiver,
            ]);

        return response()->json($response->json(), $response->status());

    }
}
