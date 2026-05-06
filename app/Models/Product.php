<?php

namespace App\Models;

use App\Enums\PricingFormulaType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
        'pricing_mode',
        'cost',
        'rsp',
        'special_price',
        'special_price_start',
        'special_price_end',
        'status',
        'is_exclusive',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'created_by',
        'pricing_formula_id',
        'override_enabled',
        'override_method',
        'override_value',
        'is_cta',
    ];

    protected $casts = [
        'is_sign_up_for_pricing' => 'boolean',
        'is_exclusive' => 'boolean',
        'is_cta' => 'boolean',
        'override_enabled' => 'boolean',
        'special_price' => 'double',
        'base_price' => 'double',
        'cost' => 'double',
        'rsp' => 'double',
        'override_value' => 'double',
        'special_price_start' => 'datetime',
        'special_price_end' => 'datetime',
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

    /**
     * Calculate the final price with priority:
     * 1. Customer-specific price (highest priority)
     * 2. Active special/promotional price
     * 3. Auto-calculated formula price (product override or brand default)
     * 4. Manual base_price
     */
    public function calculatePrice($user = null): ?float
    {
        // Priority 1: Customer-specific price
        if ($user) {
            $customerPrice = $this->customerPrices()->where('user_id', $user->id)->first();
            if ($customerPrice) {
                return (float) $customerPrice->pivot->price;
            }
        }

        // Priority 2: Active special price
        if ($this->isSpecialPriceActive()) {
            return (float) $this->special_price;
        }

        // Priority 3 & 4: Auto or Manual pricing
        if ($this->pricing_mode === 'auto') {
            $formulaPrice = $this->getFormulaPrice();
            if ($formulaPrice !== null) {
                return $formulaPrice;
            }
        }

        // Fallback: manual base_price
        return $this->base_price ? (float) $this->base_price : null;
    }

    /**
     * Calculate price using the pricing formula.
     * Uses product override if enabled, otherwise falls back to brand's formula.
     */
    public function getFormulaPrice(): ?float
    {
        if ($this->override_enabled && $this->override_method && $this->override_value !== null) {
            $method = PricingFormulaType::tryFrom($this->override_method);
            $value = (float) $this->override_value;
        } else {
            $formula = $this->pricingFormula ?? $this->brand?->pricingFormula;
            if (! $formula) {
                return null;
            }
            $method = $formula->type;
            $value = (float) $formula->value;
        }

        if (! $method || $value <= 0) {
            return null;
        }

        return $this->applyFormula($method, $value);
    }

    /**
     * Apply a pricing formula to compute the final price.
     */
    protected function applyFormula(PricingFormulaType $method, float $value): ?float
    {
        $cost = $this->cost ? (float) $this->cost : null;
        $rsp = $this->rsp ? (float) $this->rsp : null;

        return match ($method) {
            PricingFormulaType::MarginPercent => $cost && $value < 100
                ? round($cost / (1 - $value / 100), 2)
                : null,
            PricingFormulaType::MarkupPercent => $cost
                ? round($cost * (1 + $value / 100), 2)
                : null,
            PricingFormulaType::DiscountPercent => $rsp
                ? round($rsp * (1 - $value / 100), 2)
                : null,
        };
    }

    /**
     * Check if the special price is currently active.
     */
    public function isSpecialPriceActive(): bool
    {
        if (! $this->special_price) {
            return false;
        }

        $now = Carbon::now();

        // If no date range is set, special price is always active
        if (! $this->special_price_start && ! $this->special_price_end) {
            return true;
        }

        // Check start date
        if ($this->special_price_start && $now->lt($this->special_price_start)) {
            return false;
        }

        // Check end date
        if ($this->special_price_end && $now->gt($this->special_price_end)) {
            return false;
        }

        return true;
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'product_user_likes')->withTimestamps();
    }
}
