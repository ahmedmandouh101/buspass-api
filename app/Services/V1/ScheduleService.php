<?php

namespace App\Services\V1;

use App\Models\Schedule;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ScheduleService
{
    public function list(array $filters): LengthAwarePaginator
    {
        $query = Schedule::with('route')->upcoming();

        if (! empty($filters['route_id'])) {
            $query->byRoute($filters['route_id']);
        }

        if (! empty($filters['date'])) {
            $query->byDate($filters['date']);
        }

        return $query->orderBy('departure_at')->paginate(15);
    }

    public function availability(Schedule $schedule): array
    {
        return [
            'schedule_id'     => $schedule->id,
            'total_seats'     => $schedule->total_seats,
            'booked_seats'    => $schedule->booked_seats,
            'available_seats' => $schedule->availableSeats(),
            'is_available'    => $schedule->hasAvailableSeats(),
        ];
    }
}
