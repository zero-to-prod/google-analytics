<?php

namespace ZeroToProd\GoogleClient\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ZeroToProd\GoogleClient\GoogleClient
 */
class GoogleClient extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'google-client';
    }
}
