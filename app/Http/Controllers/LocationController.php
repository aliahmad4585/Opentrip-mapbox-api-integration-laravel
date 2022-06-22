<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use App\Services\LocationService;
use App\Exceptions\HttpCallException;
use App\Exceptions\LocationException;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;

class LocationController extends Controller
{

    /**
     * The place service implementation.
     *
     * @var LocationService
     */
    protected $locationService;

    /**
     * Create a new controller instance.
     *
     * @param  LocationService  $locationService
     * @return void
     */
    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\LocationRequest  $request
     * @return \Illuminate\Http\Response | App\Http\Resources
     */
    public function getLocations(LocationRequest $request)
    {
        try {
            $searchLocation =   $this->locationService->getLocations($request);
            $errors = [
                "statusCode" => 200,
                "message" => "Success",
                "error" => null,
                "data" => $searchLocation
            ];
            return new SuccessResource($searchLocation);
        } catch (HttpCallException |  LocationException | \Throwable $th) {
            $errors = [
                "statusCode" => 504,
                "message" => "Opentrip Api failed",
                "error" => $th->getMessage(),
                "data" => null
            ];
            return new ErrorResource($errors);
        }
    }
}
