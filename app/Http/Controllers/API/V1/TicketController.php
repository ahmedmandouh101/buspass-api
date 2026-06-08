<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function show(Request $request, string $code): TicketResource
    {
        $ticket = Ticket::where('code', $code)
            ->with(['booking.user', 'booking.schedule.route'])
            ->firstOrFail();

        // Only the ticket owner can view it
        $this->authorize('view', $ticket->booking);

        return new TicketResource($ticket);
    }
}
