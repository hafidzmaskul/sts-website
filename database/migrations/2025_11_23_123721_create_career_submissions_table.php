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
        Schema::create('career_submissions', function (Blueprint $table) {
            $table->id();
            
            // Foreign Key to Careers table
            $table->foreignId('career_id')
                  ->constrained('careers')
                  ->onDelete('cascade'); // If job is deleted, delete applications

            // Applicant Details
            $table->string('full_name');
            $table->string('email');
            $table->string('mobile_phone');
            $table->string('resume_path'); // Path to stored file
            $table->text('message')->nullable();
            
            // Internal Status
            $table->string('status')->default('pending')->comment('pending, reviewed, rejected, hired');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_submissions');
    }
};