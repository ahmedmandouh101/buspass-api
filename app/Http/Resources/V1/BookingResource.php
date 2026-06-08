<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'status'     => $this->status->value,
            'schedule'   => new ScheduleResource($this->whenLoaded('schedule')),
            'ticket'     => new TicketResource($this->whenLoaded('ticket')),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
