<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'brand_id',
        'title',
        'slug',
        'is_sign_up_for_pricing',
        'key_feature',
        'product_overview',
        'main_feature',
        'information',
        'specification',
        'base_price',
        'status',
        'is_exclusive',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'created_by',
        'pricing_formula_id',
    ];

    protected $casts = [
        'is_sign_up_for_pricing' => 'boolean',
        'is_exclusive' => 'boolean',
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
}
