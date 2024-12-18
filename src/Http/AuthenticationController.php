<?php

namespace MarJose123\Ninshiki\Http;

use Inertia\Inertia;

class AuthenticationController
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Auth/Login');
    }
}
