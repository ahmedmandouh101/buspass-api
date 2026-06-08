<?php

namespace App\Http\Requests\V1\Booking;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'schedule_id' => ['required', 'integer', 'exists:schedules,id'],
        ];
    }
}
