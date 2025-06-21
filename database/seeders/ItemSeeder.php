<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\ItemCategory; // Impor ItemCategory untuk mendapatkan ID
use App\Models\Restaurant; // Impor Restaurant untuk mendapatkan ID (jika Anda memiliki data restoran)

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $foodCategory = ItemCategory::where('name', 'Food')->first();
        $drinksCategory = ItemCategory::where('name', 'Drinks')->first();
        $electronicsCategory = ItemCategory::where('name', 'Electronics')->first();

        // Pastikan Anda memiliki setidaknya satu restoran di tabel 'restaurants'
        // Jika belum, buat seeder untuk restoran atau tambahkan secara manual
        $restaurant1 = Restaurant::first(); // Ambil restoran pertama

        if (!$foodCategory || !$drinksCategory || !$electronicsCategory || !$restaurant1) {
            $this->command->info('Please run ItemCategorySeeder and RestaurantSeeder first or ensure data exists.');
            return;
        }

        Item::create([
            'name' => 'Pizza Margherita',
            'image' => 'items/pizza_margherita.jpg',
            'rate' => 4.5,
            'rating' => 150,
            'type' => 'Italian Food',
            'price' => 75000,
            'item_category_id' => $foodCategory->id,
            'restaurant_id' => $restaurant1->id,
        ]);

        Item::create([
            'name' => 'Burger Combo',
            'image' => 'items/burger_combo.jpg',
            'rate' => 4.2,
            'rating' => 100,
            'type' => 'Fast Food',
            'price' => 50000,
            'item_category_id' => $foodCategory->id,
            'restaurant_id' => $restaurant1->id,
        ]);

        Item::create([
            'name' => 'Coca Cola (Small)',
            'image' => 'items/coca_cola.jpg',
            'rate' => 4.0,
            'rating' => 80,
            'type' => 'Soft Drink',
            'price' => 10000,
            'item_category_id' => $drinksCategory->id,
            'restaurant_id' => $restaurant1->id,
        ]);

        Item::create([
            'name' => 'Laptop X100',
            'image' => 'items/laptop_x100.jpg',
            'rate' => 4.8,
            'rating' => 200,
            'type' => 'Gadget',
            'price' => 12000000,
            'item_category_id' => $electronicsCategory->id,
            'restaurant_id' => $restaurant1->id,
        ]);

        // Tambahkan lebih banyak item untuk menguji pagination
        for ($i = 1; $i <= 25; $i++) {
            Item::create([
                'name' => 'Test Item ' . $i,
                'image' => 'items/test_item.jpg',
                'rate' => (double)rand(30, 50) / 10, // 3.0 to 5.0
                'rating' => rand(10, 100),
                'type' => 'Misc',
                'price' => rand(5000, 50000),
                'item_category_id' => ($i % 2 == 0 ? $foodCategory->id : $drinksCategory->id),
                'restaurant_id' => $restaurant1->id,
            ]);
        }
    }
}
