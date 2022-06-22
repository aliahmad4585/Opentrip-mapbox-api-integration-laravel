<?php

namespace App\Facades\Helpers;

use Illuminate\Support\Facades\Facade;

class HttpFacade extends Facade
{
    /**
     * Get the registered name of the HttpHelper.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'HttpCall';
    }
}
