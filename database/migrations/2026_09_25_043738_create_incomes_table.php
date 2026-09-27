<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->decimal('amount', 15, 2);
            $table->string('source')->nullable(); // Gaji, Freelance, Investasi, dll
            $table->unsignedBigInteger('category_id')->nullable();
            $table->date('date');
            $table->text('notes')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence')->nullable(); // daily, weekly, monthly
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
