<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->date('expiry_date')->nullable();
            $table->string('discount_type')->default('fixed'); // 'percentage' or 'fixed'
            $table->decimal('discount_value', 10, 2);
            $table->string('restriction_type')->nullable(); // 'role' or 'specific_user'
            $table->string('role_level')->nullable(); // 'trade account', 'credit facilities account', 'guest'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
