<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'route'           => new RouteResource($this->whenLoaded('route')),
            'departure_at'    => $this->departure_at->toDateTimeString(),
            'arrival_at'      => $this->arrival_at->toDateTimeString(),
            'price'           => $this->price,
            'total_seats'     => $this->total_seats,
            'available_seats' => $this->availableSeats(),
            'is_available'    => $this->hasAvailableSeats(),
        ];
    }
}
