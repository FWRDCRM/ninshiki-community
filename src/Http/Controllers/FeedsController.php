<?php

namespace MarJose123\Ninshiki\Http\Controllers;

use Inertia\Inertia;

class FeedsController
{
    public function index()
    {
        return Inertia::render('Feeds', []);
    }
}
