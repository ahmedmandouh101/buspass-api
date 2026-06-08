# 🚌 BusPass API

A production-ready REST API for bus route and ticket management, built with **Laravel 11** and **Sanctum**.

Users can browse routes, check schedule availability, and book seats — all through a clean, versioned JSON API.

---

## ✨ Features

- 🔐 **Auth** — Register, login, logout via Laravel Sanctum
- 🛣️ **Routes & Stops** — Admin manages routes with ordered stops
- 🕐 **Schedules** — Route trips with date/time, capacity, and seat tracking
- 🪑 **Seat Availability** — Real-time available seats per schedule
- 🎫 **Bookings & Tickets** — Book a seat, get a unique ticket code
- ❌ **Cancellations** — Cancel a booking and release the seat
- 📋 **Ticket Status Machine** — `pending → confirmed → used → cancelled`

---

## 🏗️ Tech Stack

| Layer | Tech |
|---|---|
| Framework | Laravel 11 |
| Auth | Laravel Sanctum |
| Database | MySQL |
| Architecture | Service Pattern + API Resources |
| API Version | V1 |

---

## 📁 Project Structure

```
app/
├── Enums/                   # TicketStatus, BookingStatus
├── Http/
│   ├── Controllers/API/V1/  # Thin controllers
│   ├── Requests/V1/         # Form Request validation
│   └── Resources/V1/        # API response shaping
├── Models/                  # Eloquent models
└── Services/V1/             # All business logic lives here
database/
├── migrations/
└── seeders/
routes/
└── api.php
tests/
└── Feature/V1/
docs/
└── endpoints.md             # Full endpoint reference
```

---

## 🚀 Getting Started

### 1. Clone & Install

```bash
git clone https://github.com/YOUR_USERNAME/buspass-api.git
cd buspass-api
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Configure Database

```env
DB_CONNECTION=mysql
DB_DATABASE=buspass
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Migrate & Seed

```bash
php artisan migrate --seed
```

### 4. Run

```bash
php artisan serve
```

---

## 🔑 Authentication

All protected routes require a Bearer token:

```
Authorization: Bearer {token}
```

Tokens are issued on login and can be revoked on logout.

---

## 📌 Core Endpoints (V1)

| Method | Endpoint | Description |
|---|---|---|
| POST | `/api/v1/auth/register` | Register a new user |
| POST | `/api/v1/auth/login` | Login and receive token |
| POST | `/api/v1/auth/logout` | Revoke token |
| GET | `/api/v1/routes` | List all routes |
| GET | `/api/v1/routes/{id}/stops` | List stops for a route |
| GET | `/api/v1/schedules` | List schedules (filter by route, date) |
| GET | `/api/v1/schedules/{id}/availability` | Check available seats |
| POST | `/api/v1/bookings` | Book a seat on a schedule |
| GET | `/api/v1/bookings` | List my bookings |
| POST | `/api/v1/bookings/{id}/cancel` | Cancel a booking |
| GET | `/api/v1/tickets/{code}` | Get ticket by unique code |

> Full endpoint reference: [`docs/endpoints.md`](docs/endpoints.md)

---

## 🧱 Status Machines

**Ticket Status**
```
pending → confirmed → used
                   ↘ cancelled
```

**Booking Status**
```
active → cancelled
```

---

## 🧪 Running Tests

```bash
php artisan test
```

---

## 👤 Author

**Ahmed Mandouh** — Backend Developer  
[GitHub](https://github.com/ahmedmandouh101) · [Portfolio](https://ahmedmandouh101.github.io/me) · [LinkedIn](https://linkedin.com/in/ahmedmandouh101)

---

## 📄 License

MIT
