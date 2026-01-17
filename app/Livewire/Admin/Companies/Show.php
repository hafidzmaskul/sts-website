<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use Livewire\Component;

class Show extends Component
{
    public Company $company;

    public function mount(Company $company)
    {
        $this->company = $company->load(['customers', 'creditLimits' => fn($q) => $q->latest()]);
    }

    public function render()
    {
        $this->authorize('customers.view');

        return view('livewire.admin.companies.show')->title('Company Details');
    }
}
