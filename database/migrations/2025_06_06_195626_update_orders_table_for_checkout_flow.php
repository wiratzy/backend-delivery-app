<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tambahin field order_timeout_at buat tracking timeout
            $table->timestamp('order_timeout_at')->nullable()->after('updated_at');

            // Tambahin field driver_confirmed_at buat tracking driver confirm
            $table->timestamp('driver_confirmed_at')->nullable()->after('driver_id');

            // Ubah enum status jadi yg support alur lu
            DB::statement("ALTER TABLE `orders` CHANGE `status` `status` ENUM(
                'PENDING_RESTAURANT_CONFIRMATION',
                'WAITING_DRIVER_CONFIRMATION',
                'IN_PROGRESS',
                'COMPLETED',
                'CANCELLED_TIMEOUT',
                'CANCELLED_BY_RESTAURANT',
                'CANCELLED_BY_DRIVER'
            ) NOT NULL DEFAULT 'PENDING_RESTAURANT_CONFIRMATION'");
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hapus field kalo rollback
            $table->dropColumn('order_timeout_at');
            $table->dropColumn('driver_confirmed_at');

            // Balikin enum status ke versi lama
            DB::statement("ALTER TABLE `orders` CHANGE `status` `status` ENUM(
                'pending',
                'processing',
                'shipped',
                'delivered',
                'failed'
            ) NOT NULL DEFAULT 'pending'");
        });
    }
};
