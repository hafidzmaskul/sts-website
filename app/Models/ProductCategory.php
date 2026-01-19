<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'image_path',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'created_by',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image_path ? url('storage/' . $this->image_path) : null;
    }


    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category_product');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
