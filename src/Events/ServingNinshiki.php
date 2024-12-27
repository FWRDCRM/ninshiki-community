<?php

namespace MarJose123\Ninshiki\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;

class ServingNinshiki
{
    use Dispatchable;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public Request $request
    ) {
        //
    }
}
