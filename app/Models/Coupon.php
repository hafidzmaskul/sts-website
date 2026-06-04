<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'status',
        'start_date',
        'end_date',
        'quota',
        'used_count',
        'discount_type',
        'discount_value',
        'restriction_type',
        'role_level',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'discount_value' => 'decimal:2',
        'quota' => 'integer',
        'used_count' => 'integer',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Check if the coupon is generally valid (Status, Date, Quota).
     */
    public function isValid(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }

        $now = \Carbon\Carbon::now();
        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }
        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        if ($this->quota !== null && $this->used_count >= $this->quota) {
            return false;
        }

        return true;
    }

    /**
     * Check if the user is eligible for this coupon.
     */
    public function isEligibleFor(User $user): bool
    {
        if (! $this->isValid()) {
            return false;
        }

        if ($this->restriction_type === 'role') {
            if (method_exists($user, 'hasRole')) {
                return $user->hasRole($this->role_level);
            }

            return false;
        } elseif ($this->restriction_type === 'specific_user') {
            return $this->users()->where('users.id', $user->id)->exists();
        }

        return true;
    }
}
