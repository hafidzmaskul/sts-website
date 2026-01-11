<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Check permission - using customers.view as proxy for now
        $this->authorize('customers.view');

        $companies = Company::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('registration_number', 'like', '%' . $this->search . '%')
                    ->orWhere('purchasing_contact_email', 'like', '%' . $this->search . '%');
            })
            ->with('customers')
            ->latest()
            ->paginate(10);

        $stats = [
            'total_companies' => Company::count(),
            'total_employees' => \App\Models\Customer::whereNotNull('company_id')->count(),
            'pending_reviews' => \App\Models\Customer::whereNotNull('company_id')->where('status_review', 'pending')->count(),
        ];

        return view('livewire.admin.companies.index', [
            'companies' => $companies,
            'stats' => $stats,
        ])->title('Companies');
    }
}
