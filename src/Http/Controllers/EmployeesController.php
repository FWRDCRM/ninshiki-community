<?php

namespace MarJose123\Ninshiki\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class EmployeesController
{
    /**
     * @throws ConnectionException
     */
    public function getAllEmployees(Request $request)
    {
        if ($request->wantsJson()) {
            $response = Http::ninshiki()
                ->withToken($request->session()->get('token'))
                ->withQueryParameters([
                    'page' => $request->has('page') ? $request->get('page') : 1,
                    'per_page' => $request->has('per_page') ? $request->get('per_page') : 200,
                ])
                ->get(config('ninshiki.api_version').'/users');

            $data = $response->collect('data');
            $employees = $data->map(function ($employee) {
                return [
                    'id' => $employee['id'],
                    'name' => $employee['name'],
                    'username' => $employee['username'],
                    'avatar' => $employee['avatar'],
                    'email' => $employee['email'],
                ];
            });

            return response()->json($employees);
        }
    }

    final public function index(Request $request)
    {

        $response = Http::pool(fn (Pool $pool) => [
            $pool->as('employees')
                ->ninshiki()
                ->withToken($request->session()->get('token'))
                ->withQueryParameters([
                    'page' => $request->has('page') ? $request->get('page') : 1,
                    'per_page' => $request->has('per_page') ? $request->get('per_page') : 300,
                ])
                ->get(config('ninshiki.api_version').'/users'),
            $pool->as('gift_features')
                ->ninshiki()
                ->withToken($request->session()->get('token'))
                ->get(config('ninshiki.api_version').'/application'),
            $pool->as('gift_meta')
                ->ninshiki()
                ->withToken($request->session()->get('token'))
                ->get(config('ninshiki.api_version').'/gifts/meta'),
        ]);

        $employeeData = $response['employees']->collect('data');
        $giftFeature = $response['gift_features']->json('data');
        $giftMeta = $response['gift_meta']->json();

        return Inertia::render('employee/index', [
            'employees' => $employeeData,
            'gift_feature_enable' => $giftFeature['settings']['gift']['enable'],
            'gift_type' => $giftMeta['gift_type'],
            'gift_exchange_rate' => $giftMeta['exchange_rate'],
        ]);
    }
}
