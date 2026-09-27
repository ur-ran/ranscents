<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('estimated_price', 15, 2)->nullable();
            $table->string('priority'); // urgent, important, need, want, low
            $table->string('category')->nullable(); // elektronik, fashion, makanan, dll
            $table->text('notes')->nullable();
            $table->string('url')->nullable(); // link toko online
            $table->string('store')->nullable(); // nama toko
            $table->boolean('is_purchased')->default(false);
            $table->date('target_date')->nullable(); // kapan ingin dibeli
            $table->decimal('saved_amount', 15, 2)->default(0); // sudah nabung berapa
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
