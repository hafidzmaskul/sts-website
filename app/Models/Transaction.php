<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_code',
        'customer_id',
        'subtotal',
        'tax_amount',
        'total_amount',
        'status',
        'payment_method',
        'payment_gateway_id',
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
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    protected $appends = [
        'invoice_url',
    ];

    public function getInvoiceUrlAttribute()
    {
        return 'http://sts.gaia-ol.com/payment';
    }
}
