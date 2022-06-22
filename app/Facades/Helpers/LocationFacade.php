<?php

namespace App\Facades\Helpers;

use Illuminate\Support\Facades\Facade;

class LocationFacade extends Facade
{
    /**
     * Get the registered name of the LocationHelper.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Location';
    }
}
