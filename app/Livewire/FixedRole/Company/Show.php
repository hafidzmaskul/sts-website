<?php

namespace App\Livewire\FixedRole\Company;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public function downloadStatement(\App\Models\StatementHistory $statement)
    {
        $user = Auth::user();
        $company = $user->customer?->company;

        if (! $company || $statement->company_id !== $company->id) {
            abort(403, 'Unauthorized');
        }

        if (\Illuminate\Support\Facades\Storage::exists($statement->file_path)) {
            return \Illuminate\Support\Facades\Storage::download($statement->file_path);
        }

        session()->flash('error', 'File not found.');
    }

    public function render()
    {
        $user = Auth::user();

        // Ensure user has correct role
        if (! $user->hasAnyRole(['trade account', 'credit facilities account'])) {
            abort(403, 'Unauthorized');
        }

        $company = $user->customer?->company;

        return view('livewire.fixed-role.company.show', [
            'company' => $company,
        ])->layout('components.layouts.app');
    }
}
