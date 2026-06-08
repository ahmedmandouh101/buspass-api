<?php

namespace App\Enums;

enum TicketStatus: string
{
    case Pending   = 'pending';
    case Confirmed = 'confirmed';
    case Used      = 'used';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Pending   => 'Pending Confirmation',
            self::Confirmed => 'Confirmed',
            self::Used      => 'Used',
            self::Cancelled => 'Cancelled',
        };
    }

    public function canTransitionTo(self $next): bool
    {
        return match($this) {
            self::Pending   => in_array($next, [self::Confirmed, self::Cancelled]),
            self::Confirmed => in_array($next, [self::Used, self::Cancelled]),
            self::Used      => false,
            self::Cancelled => false,
        };
    }
}
