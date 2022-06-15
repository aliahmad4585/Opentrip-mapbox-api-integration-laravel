<?php

namespace App\Services;

use Cache;

class CacheService
{

    /**
     * check key exist in cache 
     *
     * @param  string searchTerm
     * 
     * @return boolean 
     */

    public function checkIfKeyExist($key)
    {

        if (Cache::has(strtolower($key))) {
            return true;
        }

        return false;
    }


    /**
     * store the values with keys 
     *
     * @param  array  $keys
     * 
     * @return void 
     */
    public function store($key, $values)
    {
        Cache::forget($key);
        Cache::rememberForever(strtolower($key), function () use ($values) {
            return  $values;
        });
    }

    /**
     * get the values from cache based on tree
     *
     * @param  array  $keys
     * 
     * @return void 
     */
    public function get($key)
    {
        return Cache::get(strtolower($key));
    }
}
