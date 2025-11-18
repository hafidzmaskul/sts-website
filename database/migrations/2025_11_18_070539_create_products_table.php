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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('image')->nullable();
            $table->string('attachment')->nullable(); // New field
            $table->boolean('status')->default(false)->comment('0=Draft, 1=Published');
            $table->foreignId('user_id')->constrained('users')->comment('Author');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};