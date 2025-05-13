<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->default('default_restaurant.png');
            $table->decimal('rate', 3, 1)->default(0.0);
            $table->string('rating')->default('0');
            $table->string('type');
            $table->string('food_type')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_most_popular')->default(false);
            $table->foreignId('restaurant_category_id')->nullable()->constrained('restaurant_categories')->onDelete('set null');
            $table->foreignId('owner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
