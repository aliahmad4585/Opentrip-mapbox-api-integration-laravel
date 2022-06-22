<?php

namespace App\Helpers;

class LocationHelper
{

    /**
     * get number of pages .
     *
     * @param int $totalCount
     * @param int $page_size
     *
     * @return int $number_of_pages
     */

    public function getNumberOfPages(int $totalCount, int $pageSize): int
    {
        $numberOfPages = $totalCount / $pageSize;
        $expArr = explode('.', $numberOfPages);
        $fractionalDigit = isset($expArr[1]) && $expArr[1] > 0 ? 1 : 0;
        $numberOfPages = $expArr[0] + $fractionalDigit;

        return $numberOfPages;
    }


    /**
     * return the complete url for search by Geoname api
     *
     * @param string $searchTerm
     *
     * @return string $url
     */

    public function getGeoNameLocationUrl(string|null $searchTerm = null): string
    {
        $baseUrl =  config('opentrip.url.base');
        $uri =  config('opentrip.url.geoUri');
        $key = config('opentrip.secret.key');
        $queryString = "?apikey=" . $key . "&name=" . $searchTerm;
        $url = $baseUrl . $uri . $queryString;

        return $url;
    }

    /**
     * return the complete url for search of Nearbylocation
     *
     * @param int $offset
     * @param int|float $lat
     * @param int|float $lon
     *
     * @return string $url
     */

    public function getNearbyLocationsUrl(int $offset, int|float $lat, int|float $lon): string
    {
        $baseUrl =  config('opentrip.url.base');
        $uri =  config('opentrip.url.radiusUri');
        $key = config('opentrip.secret.key');
        $limit = config('opentrip.parameters.apimaxlimit');
        $radius = config('opentrip.parameters.radius') * 1000;
        $queryString = "?apikey=" . $key . "&radius=" . $radius . "&limit" . $limit . "&offset=" . $offset . "&lon=" . $lon . "&lat=" . $lat . '&rate=2&format=json';

        $url = $baseUrl . $uri . $queryString;

        return $url;
    }
}
