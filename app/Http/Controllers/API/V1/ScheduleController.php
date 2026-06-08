<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ScheduleResource;
use App\Models\Schedule;
use App\Services\V1\ScheduleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ScheduleController extends Controller
{
    public function __construct(private ScheduleService $scheduleService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $schedules = $this->scheduleService->list($request->only(['route_id', 'date']));

        return ScheduleResource::collection($schedules);
    }

    public function show(Schedule $schedule): ScheduleResource
    {
        return new ScheduleResource($schedule->load('route'));
    }

    public function availability(Schedule $schedule): JsonResponse
    {
        return response()->json([
            'data' => $this->scheduleService->availability($schedule),
        ]);
    }
}
