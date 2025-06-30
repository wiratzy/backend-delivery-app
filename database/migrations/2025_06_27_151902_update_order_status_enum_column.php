<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ganti enum kolom `status` di tabel `orders`
       DB::statement("ALTER TABLE `orders` CHANGE `status` `status` ENUM(
    'pending_confirmation',
    'waiting_driver',
    'in_progress',
    'cancelled_timeout',
    'menunggu_konfirmasi',
    'diproses',
    'diantar',
    'dibatalkan'
) NOT NULL DEFAULT 'menunggu_konfirmasi'");

    }

    public function down(): void
    {
        // Rollback ke enum sebelumnya
        DB::statement("ALTER TABLE `orders` CHANGE `status` `status` ENUM(
            'pending_confirmation',
            'waiting_driver',
            'in_progress',
            'delivered',
            'cancelled_timeout'
        ) NOT NULL DEFAULT 'pending_confirmation'");
    }
};
