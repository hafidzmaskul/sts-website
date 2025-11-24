<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Job Title
            $table->string('slug')->unique();
            $table->string('level')->nullable(); // Junior, Senior
            $table->string('employment_type')->nullable(); // Full Time, Contract
            $table->string('department')->nullable(); // Engineering
            $table->string('location')->nullable(); // Remote, London
            $table->longText('description'); // Rich Text
            $table->boolean('status')->default(false)->comment('0=Draft, 1=Published');
            $table->integer('sequence')->default(0);
            $table->foreignId('user_id')->constrained('users')->comment('Author');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('careers');
    }
};