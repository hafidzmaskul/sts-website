<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    /**
     * Generate a unique order code in the format ORD-YYYYMMDD-XXXX.
     */
    public static function generateOrderCode(): string
    {
        do {
            $code = 'ORD-'.now()->format('Ymd').'-'.strtoupper(Str::random(4));
        } while (static::where('order_code', $code)->exists());

        return $code;
    }

    protected $fillable = [
        'invoice_code',
        'order_code',
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
        'coupon_id',
        'discount_amount',
        'coupon_data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'coupon_data' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(TransactionStatusHistory::class)->orderBy('created_at', 'desc');
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    protected $appends = [
        'invoice_url',
        'saved_coupon',
    ];

    public function getInvoiceUrlAttribute()
    {
        return 'http://sts.gaia-ol.com/payment';
    }

    public function getSavedCouponAttribute()
    {
        if (! empty($this->coupon_data)) {
            return $this->coupon_data;
        }

        return $this->coupon;
    }
}
