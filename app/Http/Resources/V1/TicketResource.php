<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'code'       => $this->code,
            'status'     => $this->status->value,
            'status_label' => $this->status->label(),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
