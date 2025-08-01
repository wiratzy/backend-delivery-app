<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('restaurant_applications', function (Blueprint $table) {
            $table->string('type')->after('image'); // Setelah kolom 'image'
            $table->string('food_type')->after('type'); // Setelah kolom 'type'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_applications', function (Blueprint $table) {
            $table->dropColumn('food_type');
            $table->dropColumn('type');
        });
    }
};
