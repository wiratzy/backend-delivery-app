<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItemCategory;

class ItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ItemCategory::create(['name' => 'Electronics', 'image' => 'categories/electronics.jpg']);
        ItemCategory::create(['name' => 'Food', 'image' => 'categories/food.jpg']);
        ItemCategory::create(['name' => 'Drinks', 'image' => 'categories/drinks.jpg']);
        ItemCategory::create(['name' => 'Snacks', 'image' => 'categories/snacks.jpg']);
        // Tambahkan kategori lain sesuai kebutuhan
    }
}
