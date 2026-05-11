<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PricingFormula extends Model
{
    use HasFactory;

    protected $fillable = [
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function histories()
    {
        return $this->hasMany(PricingFormulaHistory::class);
    }

    protected static function booted()
    {
        static::created(function ($formula) {
            $formula->recordHistory();
        });

        static::updated(function ($formula) {
            $formula->recordHistory();
        });
    }

    public function recordHistory()
    {
        $this->histories()->create([
            'user_id' => Auth::id() ?? $this->user_id, // Use Auth user if available (updater), else creator
            'label' => $this->label,
            'margin' => $this->margin,
            'markup' => $this->markup,
            'discount' => $this->discount,
        ]);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
