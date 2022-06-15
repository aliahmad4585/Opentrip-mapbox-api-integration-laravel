<?php

namespace App\Services;

use App\Exceptions\HttpCallException;
use App\Exceptions\LocationException;
use HttpCall;
use Location;

class LocationService
{


    /**
     * The Cache service implementation.
     *
     * @var CacheService
     */
    protected $cacheService;

    /**
     * Create a new controller instance.
     *
     * @param  CacheService  $cacheService
     * @return void
     */
    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * get all locations.
     *
     * @param App\Http\Requests\LocationRequest  $request
     * 
     * @return array $response | $errors
     */

    public function getLocations($request)
    {
        try {
            $searchTerm =  $request->searchTerm;
            $offset =  $request->offset;
            $cacheResult = [];
            $locationCacheNameKey =  $searchTerm . '_' . config('opentrip.misc.cachenamekey');

            // check if the search term is cached with result
            if ($this->cacheService->checkIfKeyExist($searchTerm)) {
                $cacheResult = $this->getSlicedData($searchTerm, $offset);
                if (count($cacheResult)) {
                    return $this->prepareData($locationCacheNameKey, $cacheResult, $searchTerm);
                }
            }

            // if the search term not saved in cache
            // then get data from opentripapi  and save in cache

            if (count($cacheResult) == 0) {

                $response  =  $this->getSearchLocation($searchTerm);
                $searchedLocation = json_decode($response, true);
                $locationName =   $searchedLocation['name'];
                $this->storKeyWithData(
                    $searchedLocation,
                    $locationName,
                    $searchTerm,
                    $offset,
                    $locationCacheNameKey
                );
                $cacheResult = $this->getSlicedData($locationName, $offset);
                return $this->prepareData($locationCacheNameKey, $cacheResult, $searchTerm);
            }

            return [];
        } catch (HttpCallException |  LocationException | \Throwable $th) {
            throw new LocationException($th->getMessage());
        }
    }


    /**
     * prepare the data to send back to controller for response
     *
     * @param  string $locationCacheNameKey
     * @param  array $cacheResult
     * @param  string $searchTerm
     * 
     * @return array 
     */
    public function prepareData($locationCacheNameKey, $cacheResult, $searchTerm)
    {
        try {
            $searchedLocation =  $this->cacheService->get($locationCacheNameKey);
            $totalLocations =  $this->cacheService->get($searchTerm);

            return [
                "nearByLocations" => $cacheResult,
                "searchedLocation" => $searchedLocation,
                "total" => count($totalLocations)
            ];
        } catch (LocationException | \Throwable $th) {
            throw new LocationException($th->getMessage());
        }
    }


    /**
     * function used to apply the offset and return slice data
     *
     * @param  array $key
     * @param  int $offset
     * 
     * @return array 
     */

    public function getSlicedData($key, $offset)
    {
        try {
            $locations = $this->cacheService->get($key);
            return $this->applyLimitOffset($locations, $offset);
        } catch (LocationException | \Throwable $th) {
            throw new LocationException($th->getMessage());
        }
    }

    /**
     * function used to save the result in cached
     *
     * @param  array $searchedLocation
     * @param  string $locationName
     * @param  string $searchTerm
     * @param int $offset
     * @param string $locationCacheNameKey
     * 
     * @return void 
     */

    public function storKeyWithData(
        $searchedLocation,
        $locationName,
        $searchTerm,
        $offset,
        $locationCacheNameKey
    ) {
        try {
            $nearbyLocations  =  $this->getNearByLocations($searchedLocation, $offset);
            $nearbyLocations = json_decode($nearbyLocations, true);

            $cachedLocations = $this->cacheService->get($locationName) ?? [];
            $mergeLocation = array_merge($cachedLocations, $nearbyLocations);
            $this->cacheService->store($locationCacheNameKey, $searchedLocation);
            $this->cacheService->store($searchTerm, $mergeLocation);
        } catch (LocationException | \Throwable $th) {
            throw new LocationException($th->getMessage());
        }
    }

    /**
     * get location near the search location
     *
     * @param  array searchedLocation
     * @param  string searchTerm
     * 
     * @return array $response | $errors
     */

    public function getNearByLocations($searchedLocation, $offset)
    {
        try {
            $lon =  $searchedLocation['lon'];
            $lat =  $searchedLocation['lat'];
            $url =  Location::getNearbyLocationsUrl($offset, $lat, $lon);

            $method = config('http.method.get');
            $response =  HttpCall::curlExecute($method, $url);
            return $response;
        } catch (HttpCallException | \Throwable $th) {
            throw new HttpCallException($th->getMessage());
        }
    }



    /**
     * get location based on geoname .
     *
     * @param  string searchTerm
     * 
     * @return array $response | $errors
     */

    public function getSearchLocation($searchTerm)
    {

        try {
            $url =  Location::getGeoNameLocationUrl($searchTerm);
            $method = config('http.method.get');
            $response =  HttpCall::curlExecute($method, $url);
            return $response;
        } catch (HttpCallException | \Throwable $th) {
            throw new HttpCallException($th->getMessage());
        }
    }


    /**
     * get the values from array with offset limit
     *
     * @param  array $searchTerm
     * @param  int $offset
     * 
     * @return array $sliceLocation
     */

    public function applyLimitOffset($locations,  $offset)
    {
        $limit =  config('opentrip.parameters.limit');
        $slicLocations = array_slice($locations, $offset, $limit);
        return $slicLocations;
    }
}
