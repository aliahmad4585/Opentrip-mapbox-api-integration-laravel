<?php

namespace App\Services;

use Cache;
use phpDocumentor\Reflection\Types\Boolean;

class CacheService
{

    /**
     * check key exist in cache 
     *
     * @param  string $key
     * 
     * @return boolean 
     */

    public function checkIfKeyExist(string $key): bool
    {

        if (Cache::has(strtolower($key))) {
            return true;
        }

        return false;
    }


    /**
     * store the values with keys 
     *
     * @param  string $key
     * @param  array $locations
     * 
     * @return void 
     */
    public function store(string $key, array $locations): void
    {
        Cache::forget($key);
        Cache::rememberForever(strtolower($key), function () use ($locations) {
            return  $locations;
        });
    }

    /**
     * get the location from cache based on tree
     *
     * @param string  $key
     * 
     * @return array  $locations
     */
    public function get(string $key): array|null 
    {
        $locations =  Cache::get(strtolower($key));

        return $locations;
    }
}
