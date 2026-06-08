<?php
// ─────────────────────────────────────────────────────────────
// MIGRATION: create_routes_table
// ─────────────────────────────────────────────────────────────
// Schema::create('routes', function (Blueprint $table) {
//     $table->id();
//     $table->string('name');
//     $table->string('origin');
//     $table->string('destination');
//     $table->boolean('is_active')->default(true);
//     $table->timestamps();
// });

// ─────────────────────────────────────────────────────────────
// MIGRATION: create_stops_table
// ─────────────────────────────────────────────────────────────
// Schema::create('stops', function (Blueprint $table) {
//     $table->id();
//     $table->foreignId('route_id')->constrained()->cascadeOnDelete();
//     $table->string('name');
//     $table->unsignedTinyInteger('sequence');   // stop order in the route
//     $table->timestamps();
// });

// ─────────────────────────────────────────────────────────────
// MIGRATION: create_schedules_table
// ─────────────────────────────────────────────────────────────
// Schema::create('schedules', function (Blueprint $table) {
//     $table->id();
//     $table->foreignId('route_id')->constrained()->cascadeOnDelete();
//     $table->dateTime('departure_at');
//     $table->dateTime('arrival_at');
//     $table->unsignedSmallInteger('total_seats');
//     $table->unsignedSmallInteger('booked_seats')->default(0);
//     $table->decimal('price', 8, 2);
//     $table->timestamps();
// });

// ─────────────────────────────────────────────────────────────
// MIGRATION: create_bookings_table
// ─────────────────────────────────────────────────────────────
// Schema::create('bookings', function (Blueprint $table) {
//     $table->id();
//     $table->foreignId('user_id')->constrained()->cascadeOnDelete();
//     $table->foreignId('schedule_id')->constrained()->cascadeOnDelete();
//     $table->string('status')->default(\App\Enums\BookingStatus::Active->value);
//     $table->timestamps();
// });

// ─────────────────────────────────────────────────────────────
// MIGRATION: create_tickets_table
// ─────────────────────────────────────────────────────────────
// Schema::create('tickets', function (Blueprint $table) {
//     $table->id();
//     $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
//     $table->string('code')->unique();   // e.g. BPT-A3F9X2
//     $table->string('status')->default(\App\Enums\TicketStatus::Pending->value);
//     $table->timestamps();
// });
