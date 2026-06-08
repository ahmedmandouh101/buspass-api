<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\RouteResource;
use App\Http\Resources\V1\StopResource;
use App\Models\Route;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RouteController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $routes = Route::active()->with('stops')->paginate(15);

        return RouteResource::collection($routes);
    }

    public function show(Route $route): RouteResource
    {
        return new RouteResource($route->load('stops'));
    }

    public function stops(Route $route): AnonymousResourceCollection
    {
        return StopResource::collection($route->stops);
    }
}
