<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RmaRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'product_name',
        'return_reason',
        'return_type',
        'comments',
        'proof_of_purchase_path',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
