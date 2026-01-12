<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttachment extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'file_path',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
