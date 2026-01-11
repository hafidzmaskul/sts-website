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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            // Company Details
            $table->string('name');
            $table->string('registration_number')->nullable();
            $table->string('trading_name')->nullable();
            $table->string('vat_number')->nullable();
            $table->text('address')->nullable();
            $table->text('trading_address')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->text('activities_description')->nullable();

            // Contact Details - Purchasing
            $table->string('purchasing_contact_name')->nullable();
            $table->string('purchasing_contact_phone')->nullable();
            $table->string('purchasing_contact_email')->nullable();

            // Contact Details - Accounts
            $table->string('accounts_contact_name')->nullable();
            $table->string('accounts_contact_phone')->nullable();
            $table->string('accounts_contact_email')->nullable();

            // Financial Details
            $table->string('bank_name')->nullable();
            $table->text('bank_address')->nullable();
            $table->string('bank_sort_code')->nullable();
            $table->string('bank_account_number')->nullable();

            // Trade References
            $table->text('trade_ref_1_details')->nullable();
            $table->string('trade_ref_1_phone')->nullable();
            $table->string('trade_ref_1_email')->nullable();

            $table->text('trade_ref_2_details')->nullable();
            $table->string('trade_ref_2_phone')->nullable();
            $table->string('trade_ref_2_email')->nullable();

            $table->decimal('requested_credit_limit', 15, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
