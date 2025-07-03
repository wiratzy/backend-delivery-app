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
        // Modifikasi: Tambah delivery_fee di restaurants
        Schema::table('restaurants', function (Blueprint $table) {
            $table->decimal('delivery_fee', 10, 2)->default(5000.00)->after('location');
        });

        // Modifikasi: Tambah restaurant_id dan delivery_fee, hapus address dan notes di orders
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('restaurant_id')->after('user_id');
            $table->decimal('delivery_fee', 10, 2)->default(0.00)->after('total_price');
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->dropColumn('address');
            $table->dropColumn('notes');
        });

        // Kurangi: Hapus kolom location di items
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('location');
        });

        // Kurangi: Hapus tabel restaurant_categories dan kolom restaurant_category_id di restaurants
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropForeign(['restaurant_category_id']);
            $table->dropColumn('restaurant_category_id');
        });
        Schema::dropIfExists('restaurant_categories');

        // Kurangi: Hapus tabel failed_jobs
        Schema::dropIfExists('failed_jobs');

        // Kurangi: Hapus tabel password_reset_tokens
        Schema::dropIfExists('password_reset_tokens');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Balikkan: Tambah kembali tabel password_reset_tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Balikkan: Tambah kembali tabel failed_jobs
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // Balikkan: Tambah kembali tabel restaurant_categories dan kolom restaurant_category_id
        Schema::create('restaurant_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('image')->nullable();
            $table->timestamps();
        });
        Schema::table('restaurants', function (Blueprint $table) {
            $table->unsignedBigInteger('restaurant_category_id')->nullable()->after('is_most_popular');
            $table->foreign('restaurant_category_id')->references('id')->on('restaurant_categories')->onDelete('set null');
        });

        // Balikkan: Tambah kembali kolom location di items
        Schema::table('items', function (Blueprint $table) {
            $table->string('location')->nullable()->after('type');
        });

        // Balikkan: Hapus restaurant_id, delivery_fee, tambah address dan notes di orders
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['restaurant_id']);
            $table->dropColumn('restaurant_id');
            $table->dropColumn('delivery_fee');
            $table->string('address')->after('total_price');
            $table->text('notes')->nullable()->after('status');
        });

        // Balikkan: Hapus delivery_fee di restaurants
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('delivery_fee');
        });
    }
};
