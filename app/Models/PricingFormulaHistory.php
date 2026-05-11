<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingFormulaHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'pricing_formula_id',
        'user_id',
        'label',
        'margin',
        'markup',
        'discount',
    ];

    protected $casts = [
        'margin' => 'decimal:2',
        'markup' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    public function pricingFormula()
    {
        return $this->belongsTo(PricingFormula::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
