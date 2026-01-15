<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    protected $fillable = [
        'customer_id',
        'first_name',
        'last_name',
        'country',
        'city',
        'address',
        'postal_code',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
