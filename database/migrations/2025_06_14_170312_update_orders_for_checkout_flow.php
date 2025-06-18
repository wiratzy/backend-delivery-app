<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Schema::table('orders', function (Blueprint $table) {
        //     // Tambah kolom batas timeout
        //     $table->timestamp('order_timeout_at')->nullable()->after('delivery_fee');
        // });

        // Ganti enum status jadi sesuai alur checkout baru
        DB::statement("ALTER TABLE `orders` CHANGE `status` `status` ENUM(
            'pending_confirmation',
            'waiting_driver',
            'in_progress',
            'delivered',
            'cancelled_timeout'
        ) NOT NULL DEFAULT 'pending_confirmation'");
    }

    public function down(): void
    {
        // Rollback kolom timeout
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_timeout_at');
        });

        // Rollback enum status ke versi awal
        DB::statement("ALTER TABLE `orders` CHANGE `status` `status` ENUM(
            'pending',
            'processing',
            'shipped',
            'delivered',
            'failed'
        ) NOT NULL DEFAULT 'pending'");
    }
};
