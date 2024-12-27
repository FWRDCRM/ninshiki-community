<?php

namespace MarJose123\Ninshiki\Http\Middleware;

use MarJose123\Ninshiki\Events\ServingNinshiki;

class DispatchServingNinshikiEvent
{
    public function handle($request, $next)
    {
        ServingNinshiki::dispatch($request);

        return $next($request);
    }
}
