<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropProductsTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('products');
    }

    public function down()
    {

    }
}
