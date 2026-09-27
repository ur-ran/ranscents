<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->string('person_name'); // nama orang yang berhutang atau dipinjam
            $table->string('type'); // 'owe' = saya berhutang, 'lend' = saya meminjamkan
            $table->decimal('amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->date('date'); // tanggal hutang dibuat
            $table->date('due_date')->nullable(); // jatuh tempo
            $table->text('description')->nullable();
            $table->string('status')->default('active'); // active, partially_paid, paid
            $table->string('contact')->nullable(); // nomor hp / email
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
