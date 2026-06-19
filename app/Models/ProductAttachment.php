<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttachment extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'file_path',
        'is_public',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
