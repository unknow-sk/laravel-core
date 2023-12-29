<?php

namespace UnknowSk\Core\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \UnknowSk\Core\Core
 */
class Core extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \UnknowSk\Core\Core::class;
    }
}
