<?php

use Illuminate\Support\Env;

return [

    /*
    |--------------------------------------------------------------------------
    | OpenTrip API configuration
    |--------------------------------------------------------------------------
    |
    | This file is for storing the method of http. 
    | This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */
    "method" => [
        "post" => "POST",
        "get" => "GET",
        "put" => "PUT",
        "patch" => "PATCH",
        "delete" => "DELETE"
    ]

];
