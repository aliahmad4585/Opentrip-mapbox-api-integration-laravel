<?php

use Illuminate\Support\Env;

return [

    /*
    |--------------------------------------------------------------------------
    | OpenTrip API configuration
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services OpenTrip. 
    | This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */
    "secret" => [
        "key" => env('OPEN_TRIP_API_KEY')
    ],
    "api" => [
        "limit" => env('API_RATE_LIMIT', 100)
    ],
    "parameters" => [
        "limit" => env('MIX_VUE_APP_LIMIT', 20),
        "radius" => env('MIX_VUE_APP_RADIUS', 10),
        "apimaxlimit" => env('OPEN_TRIP_MAX_API_RECORDS', 500)
    ],
    'url' => [
        "base" => env('OPEN_TRIP_API_BASE_URL', 'https://api.opentripmap.com/'),
        "geoUri" => env('OPEN_TRIP_API_BASE_GEONAME_URI', '0.1/en/places/geoname'),
        "radiusUri" => env('OPEN_TRIP_API_BASE_RADIUS_URI', '0.1/en/places/radius')
    ],
    'misc' => [
        "cachenamekey" => 'real'
    ]

];
