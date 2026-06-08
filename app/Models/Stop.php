<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stop extends Model
{
    protected $fillable = [
        'route_id',
        'name',
        'sequence',
    ];

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }
}
