<?php

namespace App\Livewire\FixedRole\CreditLimits;

use App\Models\CreditLimit;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $user = Auth::user();

        // Ensure user has correct role (double check, though middleware should handle)
        if (! $user->hasRole('credit facilities account')) {
            abort(403, 'Unauthorized');
        }

        $companyId = $user->customer?->company_id;

        $creditLimits = collect();
        $currentBalance = 0;

        if ($companyId) {
            $creditLimits = CreditLimit::where('company_id', $companyId)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $latestLimit = CreditLimit::where('company_id', $companyId)
                ->latest()
                ->first();

            $currentBalance = $latestLimit ? $latestLimit->balance : 0;
        } else {
            // Handle case where user has role but no company attached?
            // Should ideally not happen data-wise but good safeguard.
            $creditLimits = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        }

        return view('livewire.fixed-role.credit-limits.index', [
            'creditLimits' => $creditLimits,
            'currentBalance' => $currentBalance,
        ])->layout('components.layouts.app');
    }
}
