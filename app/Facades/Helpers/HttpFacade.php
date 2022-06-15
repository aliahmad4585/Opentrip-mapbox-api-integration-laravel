<?php


namespace App\Facades\Helpers;

use Illuminate\Support\Facades\Facade;

class HttpFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'HttpCall';
    }
}