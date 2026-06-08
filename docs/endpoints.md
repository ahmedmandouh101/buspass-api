# BusPass API — Endpoint Reference

Base URL: `http://localhost:8000/api/v1`

---

## Auth

### POST /auth/register
Register a new user.

**Body:**
```json
{
  "name": "Ahmed Mandouh",
  "email": "ahmed@example.com",
  "password": "secret123",
  "password_confirmation": "secret123"
}
```

**Response 201:**
```json
{
  "message": "Registered successfully.",
  "token": "1|abc123...",
  "user": { "id": 1, "name": "Ahmed Mandouh", "email": "ahmed@example.com" }
}
```

---

### POST /auth/login
**Body:** `{ "email", "password" }`

**Response 200:** Same shape as register.

---

### POST /auth/logout *(auth required)*
Revokes current token.

**Response 200:** `{ "message": "Logged out successfully." }`

---

## Routes

### GET /routes
List all active routes.

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Cairo → Alexandria Express",
      "origin": "Cairo",
      "destination": "Alexandria",
      "is_active": true,
      "stops": [...]
    }
  ],
  "links": {...},
  "meta": {...}
}
```

### GET /routes/{id}
Single route with stops.

### GET /routes/{id}/stops
Ordered list of stops for the route.

---

## Schedules

### GET /schedules
List upcoming schedules. Supports filters:

| Query Param | Type | Description |
|---|---|---|
| `route_id` | int | Filter by route |
| `date` | date (Y-m-d) | Filter by departure date |

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "route": { "id": 1, "name": "Cairo → Alexandria Express", ... },
      "departure_at": "2024-06-10 08:00:00",
      "arrival_at": "2024-06-10 11:00:00",
      "price": "85.00",
      "total_seats": 40,
      "available_seats": 38,
      "is_available": true
    }
  ]
}
```

### GET /schedules/{id}
Single schedule detail.

### GET /schedules/{id}/availability
Check available seats.

**Response:**
```json
{
  "data": {
    "schedule_id": 1,
    "total_seats": 40,
    "booked_seats": 2,
    "available_seats": 38,
    "is_available": true
  }
}
```

---

## Bookings *(auth required)*

### POST /bookings
Book a seat on a schedule.

**Body:**
```json
{ "schedule_id": 1 }
```

**Response 201:**
```json
{
  "message": "Booking created successfully.",
  "data": {
    "id": 1,
    "status": "active",
    "schedule": { ... },
    "ticket": {
      "id": 1,
      "code": "BPT-A3F9X2",
      "status": "confirmed",
      "status_label": "Confirmed"
    },
    "created_at": "2024-06-08 12:00:00"
  }
}
```

**Validation errors:**
- `schedule_id` — No available seats
- `schedule_id` — Already have an active booking

### GET /bookings
List authenticated user's bookings (paginated).

### GET /bookings/{id}
Single booking (owner only, 403 otherwise).

### POST /bookings/{id}/cancel
Cancel an active booking. Releases the seat.

---

## Tickets *(auth required)*

### GET /tickets/{code}
Retrieve a ticket by its unique code (e.g. `BPT-A3F9X2`). Owner only.

**Response:**
```json
{
  "data": {
    "id": 1,
    "code": "BPT-A3F9X2",
    "status": "confirmed",
    "status_label": "Confirmed",
    "created_at": "2024-06-08 12:00:00"
  }
}
```

---

## Error Responses

### 401 Unauthenticated
```json
{ "message": "Unauthenticated." }
```

### 403 Forbidden
```json
{ "message": "This action is unauthorized." }
```

### 404 Not Found
```json
{ "message": "No query results for model [...]." }
```

### 422 Validation Error
```json
{
  "message": "The schedule_id field is required.",
  "errors": {
    "schedule_id": ["No available seats on this schedule."]
  }
}
```
