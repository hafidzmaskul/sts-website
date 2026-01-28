<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function career()
    {
        return $this->belongsTo(Career::class);
    }
}
