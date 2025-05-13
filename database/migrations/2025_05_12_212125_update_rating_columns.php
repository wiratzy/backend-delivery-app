<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRatingColumns extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->integer('rating')->default(0)->change();
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $table->integer('rating')->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('rating', 255)->default('0')->change();
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('rating', 255)->default('0')->change();
        });
    }
}
