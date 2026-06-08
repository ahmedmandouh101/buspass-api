<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    protected $fillable = [
        'route_id',
        'departure_at',
        'arrival_at',
        'total_seats',
        'booked_seats',
        'price',
    ];

    protected $casts = [
        'departure_at' => 'datetime',
        'arrival_at'   => 'datetime',
        'price'        => 'decimal:2',
    ];

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function availableSeats(): int
    {
        return $this->total_seats - $this->booked_seats;
    }

    public function hasAvailableSeats(): bool
    {
        return $this->availableSeats() > 0;
    }

    public function scopeUpcoming($query)
    {
        return $query->where('departure_at', '>', now());
    }

    public function scopeByRoute($query, int $routeId)
    {
        return $query->where('route_id', $routeId);
    }

    public function scopeByDate($query, string $date)
    {
        return $query->whereDate('departure_at', $date);
    }
}
