<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Booking\StoreBookingRequest;
use App\Http\Resources\V1\BookingResource;
use App\Models\Booking;
use App\Services\V1\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $bookings = $request->user()
            ->bookings()
            ->with(['schedule.route', 'ticket'])
            ->latest()
            ->paginate(15);

        return BookingResource::collection($bookings);
    }

    public function store(StoreBookingRequest $request): JsonResponse
    {
        $booking = $this->bookingService->book(
            $request->user(),
            $request->schedule_id
        );

        return response()->json([
            'message' => 'Booking created successfully.',
            'data'    => new BookingResource($booking),
        ], 201);
    }

    public function show(Request $request, Booking $booking): BookingResource
    {
        $this->authorize('view', $booking);

        return new BookingResource($booking->load(['schedule.route', 'ticket']));
    }

    public function cancel(Request $request, Booking $booking): JsonResponse
    {
        $this->authorize('cancel', $booking);

        $booking = $this->bookingService->cancel($booking);

        return response()->json([
            'message' => 'Booking cancelled successfully.',
            'data'    => new BookingResource($booking),
        ]);
    }
}
