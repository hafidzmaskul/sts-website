<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pricing_formulas', function (Blueprint $table) {
            $table->decimal('margin', 10, 2)->nullable()->after('label');
            $table->decimal('markup', 10, 2)->nullable()->after('margin');
            $table->decimal('discount', 10, 2)->nullable()->after('markup');
        });

        Schema::table('pricing_formula_histories', function (Blueprint $table) {
            $table->decimal('margin', 10, 2)->nullable()->after('label');
            $table->decimal('markup', 10, 2)->nullable()->after('margin');
            $table->decimal('discount', 10, 2)->nullable()->after('markup');
        });

        // Migrate existing data based on 'type' and 'value'
        DB::statement("UPDATE pricing_formulas SET margin = value WHERE type = 'margin_percent'");
        DB::statement("UPDATE pricing_formulas SET markup = value WHERE type = 'markup_percent'");
        DB::statement("UPDATE pricing_formulas SET discount = value WHERE type = 'discount_percent'");

        DB::statement("UPDATE pricing_formula_histories SET margin = value WHERE type = 'margin_percent'");
        DB::statement("UPDATE pricing_formula_histories SET markup = value WHERE type = 'markup_percent'");
        DB::statement("UPDATE pricing_formula_histories SET discount = value WHERE type = 'discount_percent'");

        Schema::table('pricing_formulas', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('value');
        });

        Schema::table('pricing_formula_histories', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pricing_formulas', function (Blueprint $table) {
            $table->string('type')->nullable();
            $table->decimal('value', 10, 2)->nullable();
        });

        Schema::table('pricing_formula_histories', function (Blueprint $table) {
            $table->string('type')->nullable();
            $table->decimal('value', 10, 2)->nullable();
        });

        // Revert data
        DB::statement("UPDATE pricing_formulas SET type = 'margin_percent', value = margin WHERE margin IS NOT NULL");
        DB::statement("UPDATE pricing_formulas SET type = 'markup_percent', value = markup WHERE markup IS NOT NULL AND value IS NULL");
        DB::statement("UPDATE pricing_formulas SET type = 'discount_percent', value = discount WHERE discount IS NOT NULL AND value IS NULL");

        DB::statement("UPDATE pricing_formula_histories SET type = 'margin_percent', value = margin WHERE margin IS NOT NULL");
        DB::statement("UPDATE pricing_formula_histories SET type = 'markup_percent', value = markup WHERE markup IS NOT NULL AND value IS NULL");
        DB::statement("UPDATE pricing_formula_histories SET type = 'discount_percent', value = discount WHERE discount IS NOT NULL AND value IS NULL");

        Schema::table('pricing_formulas', function (Blueprint $table) {
            $table->dropColumn('margin');
            $table->dropColumn('markup');
            $table->dropColumn('discount');
        });

        Schema::table('pricing_formula_histories', function (Blueprint $table) {
            $table->dropColumn('margin');
            $table->dropColumn('markup');
            $table->dropColumn('discount');
        });
    }
};
