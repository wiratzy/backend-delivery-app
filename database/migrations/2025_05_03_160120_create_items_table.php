<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->default('default_item.png');
            $table->decimal('rate', 3, 1)->default(0.0);
            $table->string('rating')->default('0');
            $table->string('type');
            $table->string('location')->nullable();
            $table->decimal('price', 8, 2)->default(0.00);
            $table->foreignId('item_category_id')->nullable()->constrained('item_categories')->onDelete('set null');
            $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
