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
        DB::statement("ALTER TABLE `orders` CHANGE `status` `status` ENUM(
    'menunggu_konfirmasi',
    'diproses',
    'diantar',
    'dibatalkan'
) NOT NULL DEFAULT 'menunggu_konfirmasi'");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
