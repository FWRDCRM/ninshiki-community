<?php

namespace MarJose123\Ninshiki\Http\Controllers;

use Inertia\Inertia;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ProfileController
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index()
    {
        return Inertia::render('profile/index', [
            'user' => session()->get('user'),
        ]);
    }
}
