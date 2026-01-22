<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Company;
use App\Models\User;

class StatementHistory extends Model
{
    protected $fillable = [
        'company_id',
        'user_id',
        'period',
        'recipients',
        'file_path',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
