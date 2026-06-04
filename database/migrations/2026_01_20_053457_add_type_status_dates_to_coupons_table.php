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
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('type')->default('redeem')->after('id'); // 'redeem' or 'claim'
            $table->string('status')->default('published')->after('type'); // 'published' or 'unpublished'
            $table->date('start_date')->nullable()->after('code');
            $table->date('end_date')->nullable()->after('start_date');
            $table->dropColumn('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['type', 'status', 'start_date', 'end_date']);
            $table->date('expiry_date')->nullable();
        });
    }
};
