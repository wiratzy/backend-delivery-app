<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user dengan role 'driver'
        $drivers = User::where('role', 'driver')->get();
        $restaurants = Restaurant::all();

        // Jika belum ada, jangan lanjut
        if ($drivers->isEmpty() || $restaurants->isEmpty()) {
            $this->command->warn('Tidak ada data user dengan role driver atau restaurant');
            return;
        }

        foreach ($drivers as $index => $user) {
            // Assign ke resto secara bergilir
            $restaurant = $restaurants[$index % $restaurants->count()];

            Driver::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'restaurant_id' => $restaurant->id,
                    'vehicle_number' => 'B1234XYZ',
                    'is_available' => true,
                    'current_latitude' => -6.4 + $index * 0.01,
                    'current_longitude' => 108.4 + $index * 0.01,
                ]
            );
        }
    }
}
