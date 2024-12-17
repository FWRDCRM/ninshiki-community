<?php

namespace MarJose123\Ninshiki\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MarJose123\Ninshiki\Ninshiki
 */
class Ninshiki extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \MarJose123\Ninshiki\Ninshiki::class;
    }
}
