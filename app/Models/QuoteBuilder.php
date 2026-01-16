<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteBuilder extends Model
{
    protected $fillable = [
        'user_id',
        'name',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_quote_builder')->withPivot('quantity');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
