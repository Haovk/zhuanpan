<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class AliasMethodFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'aliasmethod';
    }
}