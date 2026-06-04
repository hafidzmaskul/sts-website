<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'company_name',
        'email',
        'phone',
        'country',
        'postal_code',
        'project_details',
        'marketing_opt_in',
    ];

    protected $casts = [
        'marketing_opt_in' => 'boolean',
    ];

    protected static function booted()
    {
        static::created(function ($quote) {
            if ($quote->marketing_opt_in) {
                \App\Models\NewsletterSubscription::firstOrCreate(
                    ['email' => $quote->email],
                    ['source' => 'quote']
                );
            }
        });
    }
}
