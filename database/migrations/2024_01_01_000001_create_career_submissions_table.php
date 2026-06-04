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
            $table->foreignId('career_id'); // Assuming simple integer ID for now, unrelated to foreign key constraint to keep test simple or add constraint if needed.
            // Actually, best to add constraint if Career exists.
            // $table->foreignId('career_id')->constrained()->onDelete('cascade');
            // But since I just created Career migration, I can use it.

            $table->string('full_name');
            $table->string('email');
            $table->string('mobile_phone');
            $table->string('resume_path')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('pending');
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
