<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
{
    Schema::table('restaurants', function (Blueprint $table) {
        // Tambahkan baris ini
        $table->string('phone')->nullable()->after('location');
    });
}

public function down(): void
{
    Schema::table('restaurants', function (Blueprint $table) {
        // Tambahkan baris ini
        $table->dropColumn('phone');
    });
}
};
