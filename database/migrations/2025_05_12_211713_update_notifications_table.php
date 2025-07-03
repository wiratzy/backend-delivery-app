<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('notifications'); // Hapus tabel lama
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Penerima notifikasi
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null'); // Referensi pesanan
            $table->enum('type', ['order_placed', 'order_accepted', 'order_rejected', 'order_assigned', 'other']);
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
}
