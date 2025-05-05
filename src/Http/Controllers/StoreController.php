<?php

namespace MarJose123\Ninshiki\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class StoreController
{
    public function index(Request $request)
    {

        $response = Http::pool(fn (Pool $pool) => [
            $pool->as('products')->ninshiki()
                ->withToken($request->session()->get('token'))
                ->get(config('ninshiki.api_version').'/shop'),
            $pool->as('redeemed')->ninshiki()
                ->withToken($request->session()->get('token'))
                ->get(config('ninshiki.api_version').'/redeems?user='.$request->session()->get('userId')),
        ]);

        return Inertia::render('store/index', [
            'products' => $response['products']->json('data'),
            'redeem' => $response['redeemed']->json('data'),
        ]);
    }

    /**
     * @throws ConnectionException
     */
    public function redeem(Request $request)
    {
        $response = Http::ninshiki()
            ->withToken($request->session()->get('token'))
            ->post(config('ninshiki.api_version').'/redeems/shop', [
                'shop' => $request->id,
            ]);

        if ($response->status() !== 201) {
            if ($response->status() === 422) {
                throw ValidationException::withMessages($response->json()['error']['errors']);
            }
            if ($response->status() === 402) {
                return back()->withErrors(['amount' => $response->json()['message']]);
            }
            if ($response->status() === 400) {
                return back()->withErrors(['stock' => $response->json()['message']]);
            }

            return back()->withErrors(['error' => $response->json()['message']]);
        }

        return to_route('store.index');
    }

    /**
     * @throws ConnectionException
     */
    public function toggleFavorite(Request $request)
    {
        $response = Http::ninshiki()
            ->withToken($request->session()->get('token'))
            ->post(config('ninshiki.api_version').'/shop/wishlist', [
                'shop' => $request->id,
            ]);

        if ($response->status() === 422) {
            throw ValidationException::withMessages($response->json()['error']['errors']);
        }

        return to_route('store.index');
    }
}
