<?php


namespace App\Facades\Helpers;

use Illuminate\Support\Facades\Facade;

class LocationFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Location';
    }
}