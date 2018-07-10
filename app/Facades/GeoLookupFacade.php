<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class GeoLookupFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'geolookup';
    }
}