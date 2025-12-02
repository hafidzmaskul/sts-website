<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image_path',
        'status',
        'published_at',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'created_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function categories()
    {
        return $this->belongsToMany(NewsCategory::class, 'news_category_news');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }
}
