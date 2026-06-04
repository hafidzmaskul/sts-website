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
            // Add brand_id foreign key
            $table->foreignId('brand_id')->nullable()->after('id')->constrained('brands')->onDelete('set null');

            // Allow brand_name to be dropped (or keep it as nullable for now if safe transition needed)
            // User request implies replacement. Let's drop it to force usage of relation.
            $table->dropColumn('brand_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropColumn('brand_id');
            $table->string('brand_name')->nullable();
        });
    }
};
