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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Links to orders
            $table->decimal('amount', 15, 2); // Payment amount
            $table->string('method')->comment('Payment method, e.g., Cash, Mpesa, Bank Transfer');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->dateTime('paid_at')->nullable();
            $table->text('notes')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
