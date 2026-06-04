<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'image_path',
        'sequence',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image_path ? url('storage/'.$this->image_path) : null;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
