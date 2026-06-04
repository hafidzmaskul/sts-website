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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('contact_email')->nullable()->after('customer_id');
            $table->string('shipping_first_name')->nullable()->after('contact_email');
            $table->string('shipping_last_name')->nullable()->after('shipping_first_name');
            $table->text('shipping_address')->nullable()->after('shipping_last_name');
            $table->string('shipping_city')->nullable()->after('shipping_address');
            $table->string('shipping_postal_code')->nullable()->after('shipping_city');
            $table->string('shipping_country')->nullable()->after('shipping_postal_code');
            $table->string('shipping_phone_number')->nullable()->after('shipping_country');
            $table->string('shipping_method')->nullable()->after('payment_method');
            $table->decimal('shipping_price', 15, 2)->default(0)->after('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'contact_email',
                'shipping_first_name',
                'shipping_last_name',
                'shipping_address',
                'shipping_city',
                'shipping_postal_code',
                'shipping_country',
                'shipping_phone_number',
                'shipping_method',
                'shipping_price',
            ]);
        });
    }
};
