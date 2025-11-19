<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_code',
        'product_id',
        'product_name_snapshot',
        'product_attachment_snapshot',
        'price',
        'vat_amount',
        'total_amount',
        'status',
        'square_payment_id',
        'first_name',
        'last_name',
        'email',
        'mobile_phone',
        'town_city',
        'postcode',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    
    // Helper to get full name
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}