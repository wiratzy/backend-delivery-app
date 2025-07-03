<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddConstraints extends Migration
{
    public function up()
    {
        // Ubah tipe data dan tambahkan CHECK constraint untuk carts
        Schema::table('carts', function () {
            DB::statement('ALTER TABLE carts MODIFY quantity INT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE carts ADD CONSTRAINT carts_check_quantity_positive CHECK (quantity > 0)');
        });

        // Ubah tipe data dan tambahkan CHECK constraint untuk order_items
        Schema::table('order_items', function () {
            DB::statement('ALTER TABLE order_items MODIFY quantity INT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE order_items MODIFY price DECIMAL(10,2) UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE order_items ADD CONSTRAINT order_items_check_quantity_positive CHECK (quantity > 0)');
            DB::statement('ALTER TABLE order_items ADD CONSTRAINT order_items_check_price_positive CHECK (price >= 0)');
        });

        // Ubah tipe data dan tambahkan CHECK constraint untuk items
        Schema::table('items', function () {
            DB::statement('ALTER TABLE items MODIFY price DECIMAL(10,2) UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE items ADD CONSTRAINT items_check_price_positive CHECK (price >= 0)');
        });
    }

    public function down()
    {
        Schema::table('carts', function () {
            DB::statement('ALTER TABLE carts DROP CONSTRAINT carts_check_quantity_positive');
            DB::statement('ALTER TABLE carts MODIFY quantity INT NOT NULL');
        });

        Schema::table('order_items', function () {
            DB::statement('ALTER TABLE order_items DROP CONSTRAINT order_items_check_quantity_positive');
            DB::statement('ALTER TABLE order_items DROP CONSTRAINT order_items_check_price_positive');
            DB::statement('ALTER TABLE order_items MODIFY quantity INT NOT NULL');
            DB::statement('ALTER TABLE order_items MODIFY price DECIMAL(10,2) NOT NULL');
        });

        Schema::table('items', function () {
            DB::statement('ALTER TABLE items DROP CONSTRAINT items_check_price_positive');
            DB::statement('ALTER TABLE items MODIFY price DECIMAL(8,2) NOT NULL');
        });
    }
}
