<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('user_id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('email')->nullable()->after('last_name');
            $table->string('role_applied')->nullable()->after('email'); // 'trade account' or 'credit facilities account'
            $table->enum('status_review', ['pending', 'approved', 'declined'])->default('pending')->after('status');
            $table->text('review_note')->nullable()->after('status_review');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'email',
                'role_applied',
                'status_review',
                'review_note',
            ]);
        });
    }
};
