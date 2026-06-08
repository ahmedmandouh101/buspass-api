<?php

namespace Tests\Feature\V1;

use App\Enums\BookingStatus;
use App\Enums\TicketStatus;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Schedule $schedule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $route = Route::create([
            'name'        => 'Test Route',
            'origin'      => 'City A',
            'destination' => 'City B',
            'is_active'   => true,
        ]);

        $this->schedule = Schedule::create([
            'route_id'     => $route->id,
            'departure_at' => now()->addDay(),
            'arrival_at'   => now()->addDay()->addHours(3),
            'total_seats'  => 10,
            'booked_seats' => 0,
            'price'        => 50.00,
        ]);
    }

    /** @test */
    public function user_can_book_a_seat(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/bookings', [
                'schedule_id' => $this->schedule->id,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id', 'status',
                    'ticket' => ['code', 'status'],
                ],
            ]);

        $this->assertDatabaseHas('bookings', [
            'user_id'     => $this->user->id,
            'schedule_id' => $this->schedule->id,
            'status'      => BookingStatus::Active->value,
        ]);

        $this->assertEquals(1, $this->schedule->fresh()->booked_seats);
    }

    /** @test */
    public function user_cannot_book_the_same_schedule_twice(): void
    {
        $this->actingAs($this->user)
            ->postJson('/api/v1/bookings', ['schedule_id' => $this->schedule->id]);

        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/bookings', ['schedule_id' => $this->schedule->id]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['schedule_id']);
    }

    /** @test */
    public function booking_fails_when_no_seats_available(): void
    {
        $this->schedule->update(['booked_seats' => $this->schedule->total_seats]);

        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/bookings', ['schedule_id' => $this->schedule->id]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['schedule_id']);
    }

    /** @test */
    public function user_can_cancel_their_booking(): void
    {
        $bookResponse = $this->actingAs($this->user)
            ->postJson('/api/v1/bookings', ['schedule_id' => $this->schedule->id]);

        $bookingId = $bookResponse->json('data.id');

        $response = $this->actingAs($this->user)
            ->postJson("/api/v1/bookings/{$bookingId}/cancel");

        $response->assertStatus(200)
            ->assertJsonPath('data.status', BookingStatus::Cancelled->value);

        $this->assertEquals(0, $this->schedule->fresh()->booked_seats);
    }

    /** @test */
    public function user_cannot_cancel_another_users_booking(): void
    {
        $otherUser = User::factory()->create();

        $bookResponse = $this->actingAs($this->user)
            ->postJson('/api/v1/bookings', ['schedule_id' => $this->schedule->id]);

        $bookingId = $bookResponse->json('data.id');

        $response = $this->actingAs($otherUser)
            ->postJson("/api/v1/bookings/{$bookingId}/cancel");

        $response->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_user_cannot_book(): void
    {
        $this->postJson('/api/v1/bookings', ['schedule_id' => $this->schedule->id])
            ->assertStatus(401);
    }
}
