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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // ORD-YYYY-MM-XXX
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('sales_rep_id')->constrained('users')->onDelete('cascade'); // Sales rep who created order
            $table->decimal('total_amount', 15, 2);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->string('status')->default('Pending'); // Pending, Confirmed, Processing, Shipped, Delivered, Cancelled
            $table->timestamp('reserved_until')->nullable(); // for stock reservation timeout
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
