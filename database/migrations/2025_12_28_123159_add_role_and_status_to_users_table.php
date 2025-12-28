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
        Schema::table('users', function (Blueprint $table) {
            // Add role_id as a foreign key after remember_token
            $table->foreignId('role_id')->nullable()->after('remember_token')->constrained('roles')->cascadeOnDelete();

            // Add status column after role_id
            $table->enum('status', ['active', 'inactive'])->default('active')->after('role_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['role_id', 'status']);
        });
    }
};
