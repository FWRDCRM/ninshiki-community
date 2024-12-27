<?php

namespace MarJose123\Ninshiki\Concerns;

use Closure;
use Illuminate\Support\Facades\Event;
use MarJose123\Ninshiki\Events\ServingNinshiki;

trait InteractsWithEvents
{
    /**
     * Register an event listener for the Ninshiki "serving" event.
     */
    public static function serving(Closure|string|array $callback)
    {
        Event::listen(ServingNinshiki::class, $callback);
    }
}
