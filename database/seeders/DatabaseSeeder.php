<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ItemCategory;
use App\Models\RestaurantCategory;
use App\Models\Restaurant;
use App\Models\Item;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Panggil seeder Anda
        $this->call([
            // UserSeeder::class, // Jika Anda punya seeder user
            // RestaurantSeeder::class, // PENTING: Pastikan ini jalan duluan jika ItemSeeder butuh restoran
            ItemCategorySeeder::class,
            ItemSeeder::class,
            DriverSeeder::class,

        ]);
    }
}
