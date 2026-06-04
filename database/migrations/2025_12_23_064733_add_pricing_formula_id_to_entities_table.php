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
            $table->foreignId('pricing_formula_id')->nullable()->constrained('pricing_formulas')->nullOnDelete();
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->foreignId('pricing_formula_id')->nullable()->constrained('pricing_formulas')->nullOnDelete();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('pricing_formula_id')->nullable()->constrained('pricing_formulas')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['pricing_formula_id']);
            $table->dropColumn('pricing_formula_id');
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->dropForeign(['pricing_formula_id']);
            $table->dropColumn('pricing_formula_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['pricing_formula_id']);
            $table->dropColumn('pricing_formula_id');
        });
    }
};
