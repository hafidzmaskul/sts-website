<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'role_applied',
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
        'status',
        'status_review',
        'review_note',
        'account_number',
        'job_title',
        'company_id',
        'account_level',
    ];

    public function scopePending($query)
    {
        return $query->where('status_review', 'pending');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function shippingAddresses()
    {
        return $this->hasMany(ShippingAddress::class);
    }

    public function creditLimits()
    {
        return $this->hasMany(CreditLimit::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
