<?php

namespace App\Livewire\FixedRole\Company;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public function render()
    {
        $user = Auth::user();

        // Ensure user has correct role
        if (!$user->hasAnyRole(['trade account', 'credit facilities account'])) {
            abort(403, 'Unauthorized');
        }

        $company = $user->customer?->company;

        if (!$company) {
            // Should ideally not happen if user is properly set up, but handle it gracefully
            // maybe redirect or show error? For now, we'll pass null to view.
        }

        return view('livewire.fixed-role.company.show', [
            'company' => $company,
        ])->layout('components.layouts.app');
    }
}
