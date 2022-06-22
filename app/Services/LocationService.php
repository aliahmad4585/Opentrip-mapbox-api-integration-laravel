<?php

namespace App\Services;

use App\Exceptions\HttpCallException;
use App\Exceptions\LocationException;
use App\Http\Requests\LocationRequest;
use HttpCall;
use Location;

class LocationService
{


    /**
     * The Cache service implementation.
     *
     * @var CacheService
     */
    private $cacheService;

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

    public function getLocations(LocationRequest $request): array|null|LocationException
    {
        try {
            $searchTerm =  $request->searchTerm;
            $offset =  $request->offset;
            $cacheResult = [];
            $locationCacheNameKey =  $searchTerm . '_' . config('opentrip.misc.cachenamekey');

            $cacheResult =  $this->getCacheResult($searchTerm, $locationCacheNameKey, $offset);

            // if the search term not saved in cache
            // then get data from opentripapi  and save in cache

            if (count($cacheResult) == 0) {
                $cacheResult = $this->storeAndGetDataFromCache($searchTerm, $locationCacheNameKey, $offset);
            }

            return $cacheResult;
        } catch (HttpCallException |  LocationException | \Throwable $th) {
            throw new LocationException($th->getMessage());
        }
    }


    /**
     * store the data into cache and return the result
     * 
     * @param string $searchTerm
     * @param string $locationCacheNameKey
     * @param int $offset
     * 
     * @return array $cacheResult
     */

    private function storeAndGetDataFromCache(string $searchTerm, string $locationCacheNameKey, int $offset): array
    {

        $cacheResult = [];
        $response  =  $this->getSearchLocation($searchTerm);
        $searchedLocation = json_decode($response, true);
        $locationName =   $searchedLocation['name'] ?? null;
        if (!is_null($locationName)) {
            $this->storKeyWithData(
                $searchedLocation,
                $locationName,
                $searchTerm,
                $offset,
                $locationCacheNameKey
            );
            $cacheResult = $this->getSlicedData($locationName, $offset);
            if (count($cacheResult)) {
                $cacheResult = $this->prepareResponseData($locationCacheNameKey, $cacheResult, $searchTerm);
            }
        }
        return $cacheResult;
    }

    /**
     * get cache result
     * 
     * @param string $searchTerm
     * @param string $locationCacheNameKey
     * @param int $offset
     * 
     * @return array $cacheResult
     */

    private function getCacheResult(string $searchTerm, string $locationCacheNameKey, int $offset): array
    {
        $cacheResult = [];
        if ($this->cacheService->checkIfKeyExist($searchTerm)) {
            $cacheResult = $this->getSlicedData($searchTerm, $offset);
            if (count($cacheResult)) {
                $cacheResult = $this->prepareResponseData($locationCacheNameKey, $cacheResult, $searchTerm);
            }
        }
        return $cacheResult;
    }


    /**
     * prepare the data to send back to controller for response
     *
     * @param  string $locationCacheNameKey
     * @param  array $cacheResult
     * @param  string $searchTerm
     * 
     * @return array|LocationException
     */
    private function prepareResponseData(string $locationCacheNameKey, array $cacheResult, string $searchTerm): array|LocationException
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
     * @param  string $key
     * @param  int $offset
     * 
     * @return array|null|LocationException $locations|$exception
     */

    private function getSlicedData(string $key, int $offset): array|null|LocationException
    {
        try {
            $locations = $this->cacheService->get($key);
            $locations =  $this->applyLimitOffset($locations, $offset);
            return $locations;
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

    private function storKeyWithData(
        array $searchedLocation,
        string $locationName,
        string  $searchTerm,
        int $offset,
        string $locationCacheNameKey
    ): void {
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

    public function getNearByLocations(array $searchedLocation, string $offset): string|HttpCallException
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

    public function getSearchLocation(string $searchTerm): string|HttpCallException
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
     * @param  array $locations
     * @param  int $offset
     * 
     * @return array $sliceLocation
     */

    private function applyLimitOffset(array $locations,  int $offset): array
    {
        $limit =  config('opentrip.parameters.limit');
        $sliceLocations = array_slice($locations, $offset, $limit);
        return $sliceLocations;
    }
}
