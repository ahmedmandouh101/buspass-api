<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
    protected $fillable = [
        'name',
        'origin',
        'destination',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function stops(): HasMany
    {
        return $this->hasMany(Stop::class)->orderBy('sequence');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
