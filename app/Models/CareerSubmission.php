<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'career_id',
        'full_name',
        'email',
        'mobile_phone',
        'resume_path',
        'message',
        'status',
    ];

    /**
     * Get the career (job vacancy) associated with this submission.
     */
    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }
}