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
        Schema::table('products', function (Blueprint $table) {
            // Pricing mode: auto (formula-based) or manual (fixed base_price)
            $table->enum('pricing_mode', ['auto', 'manual'])->default('manual')->after('base_price');

            // Cost & RSP for formula calculations
            $table->decimal('cost', 15, 2)->nullable()->after('pricing_mode');
            $table->decimal('rsp', 15, 2)->nullable()->after('cost');

            // Product-level override of brand formula
            $table->boolean('override_enabled')->default(false)->after('pricing_formula_id');
            $table->string('override_method')->nullable()->after('override_enabled'); // margin|markup|discount
            $table->decimal('override_value', 10, 2)->nullable()->after('override_method');

            // Special price date range
            $table->dateTime('special_price_start')->nullable()->after('special_price');
            $table->dateTime('special_price_end')->nullable()->after('special_price_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'pricing_mode',
                'cost',
                'rsp',
                'override_enabled',
                'override_method',
                'override_value',
                'special_price_start',
                'special_price_end',
            ]);
        });
    }
};
