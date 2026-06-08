<?php

namespace App\Services\V1;

use App\Enums\BookingStatus;
use App\Enums\TicketStatus;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookingService
{
    /**
     * Create a booking and its ticket for the given user and schedule.
     *
     * Uses a DB transaction + lockForUpdate to prevent double-booking
     * when two users try to book the last seat at the same time.
     */
    public function book(User $user, int $scheduleId): Booking
    {
        return DB::transaction(function () use ($user, $scheduleId) {

            // Lock the schedule row so concurrent requests wait
            $schedule = Schedule::lockForUpdate()->findOrFail($scheduleId);

            $this->ensureSeatAvailable($schedule);
            $this->ensureUserHasNoActiveBooking($user, $schedule);

            // Increment booked_seats
            $schedule->increment('booked_seats');

            // Create the booking
            $booking = Booking::create([
                'user_id'     => $user->id,
                'schedule_id' => $schedule->id,
                'status'      => BookingStatus::Active,
            ]);

            // Generate unique ticket
            $booking->ticket()->create([
                'code'   => $this->generateTicketCode(),
                'status' => TicketStatus::Confirmed,
            ]);

            return $booking->load(['schedule.route', 'ticket']);
        });
    }

    /**
     * Cancel an active booking and release the seat.
     */
    public function cancel(Booking $booking): Booking
    {
        if (! $booking->isActive()) {
            throw ValidationException::withMessages([
                'booking' => 'This booking is already cancelled.',
            ]);
        }

        DB::transaction(function () use ($booking) {
            $booking->update(['status' => BookingStatus::Cancelled]);
            $booking->ticket->transitionTo(TicketStatus::Cancelled);
            $booking->schedule()->lockForUpdate()->first()->decrement('booked_seats');
        });

        return $booking->refresh()->load(['schedule.route', 'ticket']);
    }

    // ─────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────

    private function ensureSeatAvailable(Schedule $schedule): void
    {
        if (! $schedule->hasAvailableSeats()) {
            throw ValidationException::withMessages([
                'schedule_id' => 'No available seats on this schedule.',
            ]);
        }
    }

    private function ensureUserHasNoActiveBooking(User $user, Schedule $schedule): void
    {
        $alreadyBooked = Booking::where('user_id', $user->id)
            ->where('schedule_id', $schedule->id)
            ->where('status', BookingStatus::Active)
            ->exists();

        if ($alreadyBooked) {
            throw ValidationException::withMessages([
                'schedule_id' => 'You already have an active booking on this schedule.',
            ]);
        }
    }

    private function generateTicketCode(): string
    {
        do {
            $code = 'BPT-' . strtoupper(Str::random(6));
        } while (\App\Models\Ticket::where('code', $code)->exists());

        return $code;
    }
}
