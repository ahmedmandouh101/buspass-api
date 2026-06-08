<?php

namespace Database\Seeders;

use App\Models\Route;
use App\Models\Schedule;
use App\Models\Stop;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Test user
        User::factory()->create([
            'name'  => 'Ahmed Mandouh',
            'email' => 'ahmed@buspass.test',
        ]);

        // Route 1: Cairo → Alexandria
        $route1 = Route::create([
            'name'        => 'Cairo → Alexandria Express',
            'origin'      => 'Cairo',
            'destination' => 'Alexandria',
            'is_active'   => true,
        ]);

        foreach ([
            ['name' => 'Cairo Turgoman Station', 'sequence' => 1],
            ['name' => 'Giza Station',           'sequence' => 2],
            ['name' => 'Alexandria Misr Station','sequence' => 3],
        ] as $stop) {
            $route1->stops()->create($stop);
        }

        Schedule::create([
            'route_id'     => $route1->id,
            'departure_at' => now()->addDays(1)->setTime(8, 0),
            'arrival_at'   => now()->addDays(1)->setTime(11, 0),
            'total_seats'  => 40,
            'booked_seats' => 0,
            'price'        => 85.00,
        ]);

        Schedule::create([
            'route_id'     => $route1->id,
            'departure_at' => now()->addDays(1)->setTime(14, 0),
            'arrival_at'   => now()->addDays(1)->setTime(17, 0),
            'total_seats'  => 40,
            'booked_seats' => 0,
            'price'        => 85.00,
        ]);

        // Route 2: Cairo → Hurghada
        $route2 = Route::create([
            'name'        => 'Cairo → Hurghada Coast',
            'origin'      => 'Cairo',
            'destination' => 'Hurghada',
            'is_active'   => true,
        ]);

        foreach ([
            ['name' => 'Cairo Abbassiya Terminal', 'sequence' => 1],
            ['name' => 'Suez Stop',                'sequence' => 2],
            ['name' => 'Hurghada Bus Terminal',    'sequence' => 3],
        ] as $stop) {
            $route2->stops()->create($stop);
        }

        Schedule::create([
            'route_id'     => $route2->id,
            'departure_at' => now()->addDays(2)->setTime(7, 0),
            'arrival_at'   => now()->addDays(2)->setTime(13, 0),
            'total_seats'  => 35,
            'booked_seats' => 0,
            'price'        => 150.00,
        ]);
    }
}
