<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            
            // Order Details
            $table->string('invoice_code')->unique(); // e.g., INV-2024001
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->string('product_name_snapshot'); // Store name in case product is deleted
            $table->string('product_attachment_snapshot')->nullable(); // The file they bought
            
            // Financials
            $table->decimal('price', 10, 2);      // "Data 01"
            $table->decimal('vat_amount', 10, 2); // "VAT"
            $table->decimal('total_amount', 10, 2); // "Total"
            
            // Square Info
            $table->string('status')->default('pending'); // pending, paid, failed
            $table->string('square_payment_id')->nullable();
            
            // Customer Info (From your UI)
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('mobile_phone');
            $table->string('town_city');
            $table->string('postcode');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};