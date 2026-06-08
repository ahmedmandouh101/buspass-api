<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RouteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'origin'      => $this->origin,
            'destination' => $this->destination,
            'is_active'   => $this->is_active,
            'stops'       => StopResource::collection($this->whenLoaded('stops')),
            'created_at'  => $this->created_at->toDateTimeString(),
        ];
    }
}
