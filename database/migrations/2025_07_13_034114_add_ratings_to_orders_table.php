<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->unsignedTinyInteger('restaurant_rating')->nullable();
        $table->unsignedTinyInteger('item_rating')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['restaurant_rating', 'item_rating']);
    });
}
};
