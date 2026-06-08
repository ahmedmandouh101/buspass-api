<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $fillable = [
        'booking_id',
        'code',
        'status',
    ];

    protected $casts = [
        'status' => TicketStatus::class,
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function transitionTo(TicketStatus $newStatus): void
    {
        if (! $this->status->canTransitionTo($newStatus)) {
            throw new \LogicException(
                "Cannot transition ticket from [{$this->status->value}] to [{$newStatus->value}]."
            );
        }

        $this->update(['status' => $newStatus]);
    }
}
