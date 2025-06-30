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
        $table->string('status', 50)->change();
    });
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('menunggu_konfirmasi', 'diproses', 'diantar', 'diterima', 'berhasil', 'dibatalkan') DEFAULT 'menunggu_konfirmasi'");

}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('menunggu_konfirmasi', 'diproses', 'diantar', 'berhasil', 'dibatalkan') DEFAULT 'menunggu_konfirmasi'");

    }
};
