<?php

namespace MarJose123\Ninshiki\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
                    'avatar' => $employee['avatar'],
                    'email' => $employee['email'],
                ];
            });

            return response()->json($employees);
        }
    }
}
