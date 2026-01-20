<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CouponController extends Controller
{
    /**
     * List valid 'claim' coupons (auto-apply) available for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $now = Carbon::now();

        $coupons = Coupon::where('type', 'claim')
            ->where('status', 'published')
            ->where(function ($query) use ($now) {
                // Check Date Validity: Start Date is null OR past, AND End Date is null OR future
                $query->where(function ($q) use ($now) {
                    $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
                })->where(function ($q) use ($now) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
                });
            })
            ->where(function ($query) {
                // Check Quota: NULL or used_count < quota
                $query->whereNull('quota')->orWhereColumn('used_count', '<', 'quota');
            })
            ->get();

        // Filter by User Restrictions
        $availableCoupons = $coupons->filter(function ($coupon) use ($user) {
            return $this->isUserEligible($user, $coupon);
        });

        return response()->json([
            'data' => $availableCoupons->values()
        ]);
    }

    /**
     * Get a valid coupon by code.
     *
     * @param  string  $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, string $code)
    {
        $user = $request->user();

        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json(['message' => 'Coupon not found.'], 404);
        }

        // 1. Check Status
        if ($coupon->status !== 'published') {
            return response()->json(['message' => 'This coupon is not available.'], 400);
        }

        // 2. Check Date Validity
        $now = Carbon::now();
        if ($coupon->start_date && $now->lt($coupon->start_date)) {
            return response()->json(['message' => 'This coupon is not yet valid.'], 400);
        }
        if ($coupon->end_date && $now->gt($coupon->end_date)) {
            return response()->json(['message' => 'This coupon has expired.'], 400);
        }

        // 3. Check Quota
        if ($coupon->quota !== null && $coupon->used_count >= $coupon->quota) {
            return response()->json(['message' => 'This coupon has reached its usage limit.'], 400);
        }

        // 4. Check User Restriction
        if (!$this->isUserEligible($user, $coupon)) {
            return response()->json(['message' => 'You are not eligible for this coupon.'], 403);
        }

        return response()->json([
            'data' => $coupon
        ]);
    }

    /**
     * Check if user meets coupon restrictions.
     */
    private function isUserEligible($user, $coupon)
    {
        if ($coupon->restriction_type === 'role') {
            // Check based on role implementation
            if (method_exists($user, 'hasRole')) {
                return $user->hasRole($coupon->role_level);
            }
            // Fallback
            return false;
        } elseif ($coupon->restriction_type === 'specific_user') {
            return $coupon->users()->where('users.id', $user->id)->exists();
        }

        // 'none' or null restriction
        return true;
    }
}
