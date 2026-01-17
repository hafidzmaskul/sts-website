<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'brand_id',
        'title',
        'slug',
        'sku',
        'is_sign_up_for_pricing',
        'key_feature',
        'product_overview',
        'main_feature',
        'information',
        'specification',
        'base_price',
        'special_price',
        'status',
        'is_exclusive',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'created_by',
        'pricing_formula_id',
        'is_cta',
    ];

    protected $casts = [
        'is_sign_up_for_pricing' => 'boolean',
        'is_exclusive' => 'boolean',
        'is_cta' => 'boolean',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function pricingFormula()
    {
        return $this->belongsTo(PricingFormula::class);
    }

    public function categories()
    {
        return $this->belongsToMany(ProductCategory::class, 'product_category_product');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sequence');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function customerPrices()
    {
        return $this->belongsToMany(User::class, 'product_user_prices')
            ->withPivot('price')
            ->withTimestamps();
    }
    public function attachments()
    {
        return $this->hasMany(ProductAttachment::class);
    }

    protected $appends = [
        'calculated_price',
    ];

    public function getCalculatedPriceAttribute()
    {
        return $this->calculatePrice(auth()->user());
    }

    public function calculatePrice($user = null)
    {
        if ($user) {
            $customerPrice = $this->customerPrices()->where('user_id', $user->id)->first();
            if ($customerPrice) {
                return (float) $customerPrice->pivot->price;
            }
        }

        return null;
    }
}
