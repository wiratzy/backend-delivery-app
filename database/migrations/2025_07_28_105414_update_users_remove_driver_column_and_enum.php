<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1. Hapus kolom is_active_driver
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_active_driver')) {
                $table->dropColumn('is_active_driver');
            }
        });

        // 2. Ubah ENUM role dan hapus nilai 'driver'
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'customer', 'owner') NOT NULL");
    }

    public function down(): void
    {
        // Tambahkan kembali kolom is_active_driver
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active_driver')->default(false);
        });

        // Tambahkan kembali enum 'driver' ke kolom role
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'customer', 'owner', 'driver') NOT NULL");
    }
};
