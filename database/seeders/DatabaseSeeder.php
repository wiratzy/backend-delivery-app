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
    public function run(): void
    {
        // Users
        // User::create([
        //     'name' => 'Customer User',
        //     'address' => 'Customer Address',
        //     'phone' => '123456789',
        //     'email' => 'customer@example.com',
        //     'password' => bcrypt('password'),
        //     'role' => 'customer',
        // ]);

        // $owner = User::create([
        //     'name' => 'Restaurant Owner',
        //     'address' => 'Owner Address',
        //     'phone' => '987654321',
        //     'email' => 'owner@example.com',
        //     'password' => bcrypt('password'),
        //     'role' => 'restaurant_owner',
        // ]);

        // User::create([
        //     'name' => 'Driver',
        //     'address' => 'Driver Address',
        //     'phone' => '555555555',
        //     'email' => 'driver@example.com',
        //     'password' => bcrypt('password'),
        //     'role' => 'driver',
        // ]);

        User::create([
            'name' => 'Admin User',
            'address' => 'Admin Address',
            'phone' => '111111111',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // // Item Categories
        // $itemCategory = ItemCategory::create([
        //     'name' => 'Sri Lanka',
        //     'image' => 'sri_lanka.jpg',
        // ]);

        // Restaurant Categories
        // $restaurantCategory = RestaurantCategory::create([
        //     'name' => 'Fast Food',
        //     'image' => 'fast_food.jpg',
        // ]);

        // // Restaurants
        // $restaurant = Restaurant::create([
        //     'name' => 'Pizza Palembang',
        //     'image' => 'pizza_palembang.jpg',
        //     'rate' => 4.5,
        //     'rating' => 4.5,
        //     'type' => 'Fast Food',
        //     'food_type' => 'Pizza',
        //     'location' => 'Palembang',
        //     'is_most_popular' => true,
        //     'restaurant_category_id' => $restaurantCategory->id,
        //     'owner_id' => $owner->id,
        // ]);

        // // Items
        // Item::create([
        //     'name' => 'Pizza Margherita',
        //     'image' => 'pizza_margherita.jpg',
        //     'rating' => 4.5,
        //     'type' => 'Fast Food',
        //     'location' => 'Palembang',
        //     'item_category_id' => $itemCategory->id,
        //     'restaurant_id' => $restaurant->id,
        // ]);
    }
}
