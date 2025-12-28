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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // primary key
            $table->string('username');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique(); // ensures no duplicate emails
            $table->string('password'); // hashed passwords
            $table->enum('role', ['Sales Manager', 'Sales Representative']); // two user types only
            $table->json('permissions')->nullable(); // allows flexible permissions storage
            $table->enum('status', ['active', 'inactive'])->default('active'); // user status
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
