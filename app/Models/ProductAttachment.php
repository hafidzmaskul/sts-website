<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    /**
     * @var list<string>
     */
    protected $appends = [
        'file_url',
    ];

    public function getFileUrlAttribute(): ?string
    {
        if (! $this->file_path) {
            return null;
        }

        if (Str::startsWith($this->file_path, ['http://', 'https://'])) {
            return $this->file_path;
        }

        return Storage::disk('public')->url($this->file_path);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
