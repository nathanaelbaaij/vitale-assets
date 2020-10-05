<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\BreachLocation;
use App\Http\Resources\BreachLocationResource;
use App\Http\Resources\BreachLocationsResource;

/**
 * Class AssetsAPIController
 * @package App\Http\Controllers
 */
class BreachLocationsAPIController extends Controller
{
    /**
     * @return BreachLocationsResource
     */
    public function index()
    {
        //remove data wrap
        BreachLocationsResource::withoutWrapping();
        $breachLocations = BreachLocation::all();
        return new BreachLocationsResource($breachLocations);
    }

    /**
     * @param BreachLocation $breachLocation
     * @return BreachLocationResource
     */
    public function show(BreachLocation $breachLocation)
    {
        //remove data wrap
        BreachLocationsResource::withoutWrapping();
        return new BreachLocationResource($breachLocation);
    }
}
