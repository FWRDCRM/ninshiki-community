<?php

namespace MarJose123\Ninshiki\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class GiftController
{
    /**
     * @throws ConnectionException
     */
    public function send(Request $request)
    {
        $response = Http::ninshiki()
            ->withToken($request->session()->get('token'))
            ->post(config('ninshiki.api_version').'/gifts/send', [
                'type' => $request->type,
                'amount' => $request->amount,
                'receiver' => $request->receiver,
            ]);

        if ($response->status() === 422) {
            throw ValidationException::withMessages($response->json()['error']['errors']);
        }

        return to_route('employees.list');

    }
}
