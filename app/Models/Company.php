<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'registration_number',
        'trading_name',
        'vat_number',
        'address',
        'trading_address',
        'phone',
        'fax',
        'activities_description',
        'purchasing_contact_name',
        'purchasing_contact_phone',
        'purchasing_contact_email',
        'accounts_contact_name',
        'accounts_contact_phone',
        'accounts_contact_email',
        'bank_name',
        'bank_address',
        'bank_sort_code',
        'bank_account_number',
        'trade_ref_1_details',
        'trade_ref_1_phone',
        'trade_ref_1_email',
        'trade_ref_2_details',
        'trade_ref_2_phone',
        'trade_ref_2_email',
        'requested_credit_limit',
    ];

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function creditLimits(): HasMany
    {
        return $this->hasMany(CreditLimit::class);
    }
}
