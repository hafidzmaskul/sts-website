<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'website',
        'is_active',
        'sort_order',
        'pricing_formula_id',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image ? url('storage/'.$this->image) : null;
    }

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function pricingFormula()
    {
        return $this->belongsTo(PricingFormula::class);
    }
}
