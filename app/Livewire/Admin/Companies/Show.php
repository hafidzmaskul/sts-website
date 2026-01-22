<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use Livewire\Component;

class Show extends Component
{
    public Company $company;

    public $monthlyCreditLimits;
    public $showAddLimitModal = false;
    public $newLimitAmount;
    public $newLimitDescription;

    protected $rules = [
        'newLimitAmount' => 'required|numeric|min:0',
        'newLimitDescription' => 'nullable|string|max:255',
    ];

    public function mount(Company $company)
    {
        $this->company = $company->load(['customers', 'creditLimits' => fn($q) => $q->latest()]);
        $this->refreshMonthlyLimits();
    }

    public function refreshMonthlyLimits()
    {
        $this->monthlyCreditLimits = \App\Models\MonthlyCreditLimit::with('user')
            ->where('company_id', $this->company->id)
            ->latest()
            ->get();
    }

    public function confirmAddLimit()
    {
        $this->reset(['newLimitAmount', 'newLimitDescription']);
        $this->showAddLimitModal = true;
    }

    public function saveLimit()
    {
        $this->validate();

        \App\Models\MonthlyCreditLimit::create([
            'company_id' => $this->company->id,
            'user_id' => auth()->id(),
            'amount' => $this->newLimitAmount,
            'description' => $this->newLimitDescription ?? 'Manual entry',
        ]);

        $this->showAddLimitModal = false;
        $this->refreshMonthlyLimits();

        $this->dispatch('notify', type: 'success', message: 'Monthly credit limit added successfully.');
    }

    public function render()
    {
        $this->authorize('customers.view');

        return view('livewire.admin.companies.show')->title('Company Details');
    }
}
