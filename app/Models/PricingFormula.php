<?php

namespace App\Models;

use App\Enums\PricingFormulaType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PricingFormula extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'type',
        'value',
    ];

    protected $casts = [
        'type' => PricingFormulaType::class,
        'value' => 'decimal:2',
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
            'type' => $this->type,
            'value' => $this->value,
        ]);
    }
}
